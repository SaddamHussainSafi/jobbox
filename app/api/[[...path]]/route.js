import { connectDB } from '@/lib/db';
import { createUser, authenticateUser, getUserById } from '@/lib/auth';
import { createSession, getSession, destroySession } from '@/lib/session';
import { NextResponse } from 'next/server';
import { v4 as uuidv4 } from 'uuid';
import { openai } from '@ai-sdk/openai';
import { streamText } from 'ai';

// Helper function to get user from session
async function getCurrentUser(request) {
  const session = await getSession();
  if (!session) {
    return null;
  }
  return await getUserById(session.userId);
}

// Register endpoint
export async function POST(request) {
  const url = new URL(request.url);
  const pathname = url.pathname;
  
  try {
    if (pathname === '/api/auth/register') {
      const { email, password, name } = await request.json();
      
      if (!email || !password || !name) {
        return NextResponse.json({ error: 'Missing required fields' }, { status: 400 });
      }
      
      const user = await createUser(email, password, name);
      await createSession(user.id);
      
      return NextResponse.json({ user }, { status: 201 });
    }
    
    if (pathname === '/api/auth/login') {
      const { email, password } = await request.json();
      
      if (!email || !password) {
        return NextResponse.json({ error: 'Missing credentials' }, { status: 400 });
      }
      
      try {
        const user = await authenticateUser(email, password);
        await createSession(user.id);
        
        return NextResponse.json({ user });
      } catch (error) {
        const status = error.status || 500;
        return NextResponse.json({ error: error.message }, { status });
      }
    }
    
    if (pathname === '/api/auth/logout') {
      await destroySession();
      return NextResponse.json({ success: true });
    }
    
    if (pathname === '/api/applications') {
      const user = await getCurrentUser(request);
      if (!user) {
        return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
      }
      
      const { company, position, jobDescription, status = 'applied' } = await request.json();
      
      if (!company || !position || !jobDescription) {
        return NextResponse.json({ error: 'Missing required fields' }, { status: 400 });
      }
      
      const db = await connectDB();
      const applications = db.collection('applications');
      
      const application = {
        id: uuidv4(),
        userId: user.id,
        company,
        position,
        jobDescription,
        status,
        appliedDate: new Date(),
        resume: null,
        coverLetter: null
      };
      
      await applications.insertOne(application);
      
      return NextResponse.json({ application }, { status: 201 });
    }
    
    if (pathname === '/api/profile') {
      const user = await getCurrentUser(request);
      if (!user) {
        return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
      }
      
      const profileData = await request.json();
      const db = await connectDB();
      const profiles = db.collection('profiles');
      
      const profile = {
        id: uuidv4(),
        userId: user.id,
        ...profileData,
        updatedAt: new Date()
      };
      
      await profiles.replaceOne({ userId: user.id }, profile, { upsert: true });
      
      return NextResponse.json({ profile });
    }
    
    if (pathname === '/api/generate') {
      const user = await getCurrentUser(request);
      if (!user) {
        return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
      }
      
      const { documentType, applicationId } = await request.json();
      
      if (!documentType || !applicationId) {
        return NextResponse.json({ error: 'Missing required fields' }, { status: 400 });
      }
      
      const db = await connectDB();
      const applications = db.collection('applications');
      const profiles = db.collection('profiles');
      
      const application = await applications.findOne({ id: applicationId, userId: user.id });
      if (!application) {
        return NextResponse.json({ error: 'Application not found' }, { status: 404 });
      }
      
      const profile = await profiles.findOne({ userId: user.id });
      if (!profile) {
        return NextResponse.json({ error: 'Profile not found. Please complete your profile first.' }, { status: 404 });
      }
      
      let prompt;
      if (documentType === 'resume') {
        prompt = `Generate a professional resume for ${profile.name} targeting a ${application.position} role at ${application.company}.

Job Description:
${application.jobDescription}

Candidate Information:
- Name: ${profile.name}
- Email: ${profile.email}
- Phone: ${profile.phone || 'Not provided'}
- Skills: ${profile.skills ? profile.skills.join(', ') : 'Not provided'}
- Experience: ${profile.experience ? profile.experience.map(exp => 
  `${exp.title} at ${exp.company} (${exp.startDate} - ${exp.endDate || 'Present'}): ${exp.description}`
).join('\n') : 'Not provided'}
- Education: ${profile.education ? profile.education.map(edu => 
  `${edu.degree} from ${edu.institution} (${edu.graduationDate})`
).join('\n') : 'Not provided'}

Create a professional resume with these sections:
1. Contact Information
2. Professional Summary (2-3 sentences)
3. Skills (relevant to the job)
4. Work Experience (if available)
5. Education (if available)

Format in clean, professional text. Focus on relevant skills and experiences that match the job description.`;
      } else {
        prompt = `Write a professional cover letter for ${profile.name} applying for ${application.position} at ${application.company}.

Job Description:
${application.jobDescription}

Candidate Information:
- Name: ${profile.name}
- Email: ${profile.email}
- Skills: ${profile.skills ? profile.skills.join(', ') : 'Not provided'}
- Experience: ${profile.experience ? profile.experience.map(exp => 
  `${exp.title} at ${exp.company}: ${exp.description}`
).join('\n') : 'Not provided'}
- Education: ${profile.education ? profile.education.map(edu => 
  `${edu.degree} from ${edu.institution}`
).join('\n') : 'Not provided'}

Write a compelling cover letter that:
1. Introduces the candidate professionally
2. Explains why they're interested in the position and company
3. Highlights relevant skills and experiences that match the job requirements
4. Shows enthusiasm and cultural fit
5. Concludes with a call to action

Keep it under 400 words with a professional, engaging tone.`;
      }
      
      const result = await streamText({
        model: openai('gpt-4'),
        system: 'You are an expert career coach and resume writer. Create professional, tailored documents that highlight relevant skills and experiences.',
        messages: [{ role: 'user', content: prompt }]
      });
      
      return result.toDataStreamResponse();
    }
    
    return NextResponse.json({ error: 'Endpoint not found' }, { status: 404 });
    
  } catch (error) {
    console.error('API Error:', error);
    return NextResponse.json({ error: error.message || 'Internal server error' }, { status: 500 });
  }
}

export async function GET(request) {
  const url = new URL(request.url);
  const pathname = url.pathname;
  
  try {
    if (pathname === '/api/auth/me') {
      const user = await getCurrentUser(request);
      if (!user) {
        return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
      }
      return NextResponse.json({ user });
    }
    
    if (pathname === '/api/applications') {
      const user = await getCurrentUser(request);
      if (!user) {
        return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
      }
      
      const db = await connectDB();
      const applications = db.collection('applications');
      const userApplications = await applications.find({ userId: user.id }).sort({ appliedDate: -1 }).toArray();
      
      return NextResponse.json({ applications: userApplications });
    }
    
    if (pathname === '/api/profile') {
      const user = await getCurrentUser(request);
      if (!user) {
        return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
      }
      
      const db = await connectDB();
      const profiles = db.collection('profiles');
      const profile = await profiles.findOne({ userId: user.id });
      
      return NextResponse.json({ profile });
    }
    
    if (pathname.startsWith('/api/applications/')) {
      const user = await getCurrentUser(request);
      if (!user) {
        return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
      }
      
      const applicationId = pathname.split('/')[3];
      const db = await connectDB();
      const applications = db.collection('applications');
      const application = await applications.findOne({ id: applicationId, userId: user.id });
      
      if (!application) {
        return NextResponse.json({ error: 'Application not found' }, { status: 404 });
      }
      
      return NextResponse.json({ application });
    }
    
    return NextResponse.json({ message: 'Careero API is running' });
    
  } catch (error) {
    console.error('API Error:', error);
    return NextResponse.json({ error: error.message || 'Internal server error' }, { status: 500 });
  }
}

export async function PUT(request) {
  const url = new URL(request.url);
  const pathname = url.pathname;
  
  try {
    if (pathname.startsWith('/api/applications/')) {
      const user = await getCurrentUser(request);
      if (!user) {
        return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
      }
      
      const applicationId = pathname.split('/')[3];
      const updates = await request.json();
      
      const db = await connectDB();
      const applications = db.collection('applications');
      
      const result = await applications.updateOne(
        { id: applicationId, userId: user.id },
        { $set: { ...updates, updatedAt: new Date() } }
      );
      
      if (result.matchedCount === 0) {
        return NextResponse.json({ error: 'Application not found' }, { status: 404 });
      }
      
      const updatedApplication = await applications.findOne({ id: applicationId, userId: user.id });
      return NextResponse.json({ application: updatedApplication });
    }
    
    return NextResponse.json({ error: 'Endpoint not found' }, { status: 404 });
    
  } catch (error) {
    console.error('API Error:', error);
    return NextResponse.json({ error: error.message || 'Internal server error' }, { status: 500 });
  }
}

export async function DELETE(request) {
  const url = new URL(request.url);
  const pathname = url.pathname;
  
  try {
    if (pathname.startsWith('/api/applications/')) {
      const user = await getCurrentUser(request);
      if (!user) {
        return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
      }
      
      const applicationId = pathname.split('/')[3];
      
      const db = await connectDB();
      const applications = db.collection('applications');
      
      const result = await applications.deleteOne({ id: applicationId, userId: user.id });
      
      if (result.deletedCount === 0) {
        return NextResponse.json({ error: 'Application not found' }, { status: 404 });
      }
      
      return NextResponse.json({ success: true });
    }
    
    return NextResponse.json({ error: 'Endpoint not found' }, { status: 404 });
    
  } catch (error) {
    console.error('API Error:', error);
    return NextResponse.json({ error: error.message || 'Internal server error' }, { status: 500 });
  }
}