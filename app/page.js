'use client';

import { useState, useEffect } from 'react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Textarea } from '@/components/ui/textarea';
import { Plus, User, Briefcase, FileText, LogOut, Trash2, Edit } from 'lucide-react';
import { useCompletion } from 'ai/react';

export default function App() {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [currentView, setCurrentView] = useState('dashboard');
  const [applications, setApplications] = useState([]);
  const [profile, setProfile] = useState(null);
  const [editingApplication, setEditingApplication] = useState(null);
  const [generatingDoc, setGeneratingDoc] = useState(null);
  
  // Authentication forms
  const [loginForm, setLoginForm] = useState({ email: '', password: '' });
  const [registerForm, setRegisterForm] = useState({ name: '', email: '', password: '' });
  const [isLogin, setIsLogin] = useState(true);
  
  // Application form
  const [applicationForm, setApplicationForm] = useState({
    company: '',
    position: '',
    jobDescription: '',
    status: 'applied'
  });
  
  // Profile form
  const [profileForm, setProfileForm] = useState({
    name: '',
    email: '',
    phone: '',
    skills: [''],
    experience: [{
      title: '',
      company: '',
      startDate: '',
      endDate: '',
      description: ''
    }],
    education: [{
      degree: '',
      institution: '',
      graduationDate: ''
    }]
  });
  
  const { completion, complete, isLoading: isGeneratingDoc } = useCompletion({
    api: '/api/generate',
    onFinish: (result) => {
      // Save the generated document
      saveGeneratedDocument(result);
    }
  });
  
  useEffect(() => {
    checkAuth();
  }, []);
  
  const checkAuth = async () => {
    try {
      const response = await fetch('/api/auth/me');
      if (response.ok) {
        const data = await response.json();
        setUser(data.user);
        loadApplications();
        loadProfile();
      }
    } catch (error) {
      console.error('Auth check failed:', error);
    } finally {
      setLoading(false);
    }
  };
  
  const loadApplications = async () => {
    try {
      const response = await fetch('/api/applications');
      if (response.ok) {
        const data = await response.json();
        setApplications(data.applications || []);
      }
    } catch (error) {
      console.error('Failed to load applications:', error);
    }
  };
  
  const loadProfile = async () => {
    try {
      const response = await fetch('/api/profile');
      if (response.ok) {
        const data = await response.json();
        if (data.profile) {
          setProfile(data.profile);
          setProfileForm(data.profile);
        }
      }
    } catch (error) {
      console.error('Failed to load profile:', error);
    }
  };
  
  const handleLogin = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch('/api/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(loginForm)
      });
      
      if (response.ok) {
        const data = await response.json();
        setUser(data.user);
        setCurrentView('dashboard');
        loadApplications();
        loadProfile();
      } else {
        const error = await response.json();
        alert(error.error || 'Login failed');
      }
    } catch (error) {
      console.error('Login error:', error);
      alert('Login failed');
    }
  };
  
  const handleRegister = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch('/api/auth/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(registerForm)
      });
      
      if (response.ok) {
        const data = await response.json();
        setUser(data.user);
        setCurrentView('dashboard');
        loadApplications();
      } else {
        const error = await response.json();
        alert(error.error || 'Registration failed');
      }
    } catch (error) {
      console.error('Registration error:', error);
      alert('Registration failed');
    }
  };
  
  const handleLogout = async () => {
    try {
      await fetch('/api/auth/logout', { method: 'POST' });
      setUser(null);
      setApplications([]);
      setProfile(null);
      setCurrentView('dashboard');
    } catch (error) {
      console.error('Logout error:', error);
    }
  };
  
  const handleAddApplication = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch('/api/applications', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(applicationForm)
      });
      
      if (response.ok) {
        const data = await response.json();
        setApplications([data.application, ...applications]);
        setApplicationForm({ company: '', position: '', jobDescription: '', status: 'applied' });
        setCurrentView('dashboard');
      } else {
        const error = await response.json();
        alert(error.error || 'Failed to add application');
      }
    } catch (error) {
      console.error('Add application error:', error);
      alert('Failed to add application');
    }
  };
  
  const handleUpdateApplication = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch(`/api/applications/${editingApplication.id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(applicationForm)
      });
      
      if (response.ok) {
        const data = await response.json();
        setApplications(applications.map(app => 
          app.id === editingApplication.id ? data.application : app
        ));
        setEditingApplication(null);
        setApplicationForm({ company: '', position: '', jobDescription: '', status: 'applied' });
        setCurrentView('dashboard');
      } else {
        const error = await response.json();
        alert(error.error || 'Failed to update application');
      }
    } catch (error) {
      console.error('Update application error:', error);
      alert('Failed to update application');
    }
  };
  
  const handleDeleteApplication = async (applicationId) => {
    if (confirm('Are you sure you want to delete this application?')) {
      try {
        const response = await fetch(`/api/applications/${applicationId}`, {
          method: 'DELETE'
        });
        
        if (response.ok) {
          setApplications(applications.filter(app => app.id !== applicationId));
        } else {
          const error = await response.json();
          alert(error.error || 'Failed to delete application');
        }
      } catch (error) {
        console.error('Delete application error:', error);
        alert('Failed to delete application');
      }
    }
  };
  
  const handleSaveProfile = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch('/api/profile', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(profileForm)
      });
      
      if (response.ok) {
        const data = await response.json();
        setProfile(data.profile);
        setCurrentView('dashboard');
      } else {
        const error = await response.json();
        alert(error.error || 'Failed to save profile');
      }
    } catch (error) {
      console.error('Save profile error:', error);
      alert('Failed to save profile');
    }
  };
  
  const generateDocument = async (applicationId, documentType) => {
    if (!profile) {
      alert('Please complete your profile first to generate documents.');
      setCurrentView('profile');
      return;
    }
    
    setGeneratingDoc({ applicationId, documentType });
    await complete('', {
      body: {
        applicationId,
        documentType
      }
    });
  };
  
  const saveGeneratedDocument = async (content) => {
    if (!generatingDoc) return;
    
    try {
      const response = await fetch(`/api/applications/${generatingDoc.applicationId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          [generatingDoc.documentType]: content
        })
      });
      
      if (response.ok) {
        const data = await response.json();
        setApplications(applications.map(app => 
          app.id === generatingDoc.applicationId ? data.application : app
        ));
      }
    } catch (error) {
      console.error('Failed to save generated document:', error);
    } finally {
      setGeneratingDoc(null);
    }
  };
  
  const startEdit = (application) => {
    setEditingApplication(application);
    setApplicationForm({
      company: application.company,
      position: application.position,
      jobDescription: application.jobDescription,
      status: application.status
    });
    setCurrentView('add-application');
  };
  
  const addSkill = () => {
    setProfileForm({
      ...profileForm,
      skills: [...profileForm.skills, '']
    });
  };
  
  const removeSkill = (index) => {
    const newSkills = [...profileForm.skills];
    newSkills.splice(index, 1);
    setProfileForm({
      ...profileForm,
      skills: newSkills
    });
  };
  
  const updateSkill = (index, value) => {
    const newSkills = [...profileForm.skills];
    newSkills[index] = value;
    setProfileForm({
      ...profileForm,
      skills: newSkills
    });
  };
  
  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
          <p className="text-gray-600">Loading...</p>
        </div>
      </div>
    );
  }
  
  if (!user) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gray-50">
        <div className="max-w-md w-full space-y-8">
          <div className="text-center">
            <h2 className="mt-6 text-3xl font-bold text-gray-900">Careero</h2>
            <p className="mt-2 text-sm text-gray-600">
              Your AI-powered job application manager
            </p>
          </div>
          
          <Card className="mt-8">
            <CardHeader>
              <CardTitle>{isLogin ? 'Sign In' : 'Create Account'}</CardTitle>
              <CardDescription>
                {isLogin ? 'Welcome back!' : 'Get started with your job search'}
              </CardDescription>
            </CardHeader>
            <CardContent>
              <form onSubmit={isLogin ? handleLogin : handleRegister} className="space-y-4">
                {!isLogin && (
                  <div>
                    <Label htmlFor="name">Full Name</Label>
                    <Input
                      id="name"
                      type="text"
                      value={registerForm.name}
                      onChange={(e) => setRegisterForm({...registerForm, name: e.target.value})}
                      required
                    />
                  </div>
                )}
                
                <div>
                  <Label htmlFor="email">Email</Label>
                  <Input
                    id="email"
                    type="email"
                    value={isLogin ? loginForm.email : registerForm.email}
                    onChange={(e) => {
                      const value = e.target.value;
                      if (isLogin) {
                        setLoginForm({...loginForm, email: value});
                      } else {
                        setRegisterForm({...registerForm, email: value});
                      }
                    }}
                    required
                  />
                </div>
                
                <div>
                  <Label htmlFor="password">Password</Label>
                  <Input
                    id="password"
                    type="password"
                    value={isLogin ? loginForm.password : registerForm.password}
                    onChange={(e) => {
                      const value = e.target.value;
                      if (isLogin) {
                        setLoginForm({...loginForm, password: value});
                      } else {
                        setRegisterForm({...registerForm, password: value});
                      }
                    }}
                    required
                  />
                </div>
                
                <Button type="submit" className="w-full">
                  {isLogin ? 'Sign In' : 'Create Account'}
                </Button>
              </form>
              
              <div className="mt-4 text-center">
                <button
                  type="button"
                  onClick={() => setIsLogin(!isLogin)}
                  className="text-sm text-blue-600 hover:text-blue-500"
                >
                  {isLogin ? "Don't have an account? Sign up" : "Already have an account? Sign in"}
                </button>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    );
  }
  
  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <header className="bg-white shadow-sm border-b">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <div className="flex items-center">
              <h1 className="text-2xl font-bold text-gray-900">Careero</h1>
            </div>
            
            <nav className="flex space-x-4">
              <Button
                variant={currentView === 'dashboard' ? 'default' : 'ghost'}
                onClick={() => setCurrentView('dashboard')}
              >
                <Briefcase className="h-4 w-4 mr-2" />
                Dashboard
              </Button>
              
              <Button
                variant={currentView === 'add-application' ? 'default' : 'ghost'}
                onClick={() => {
                  setCurrentView('add-application');
                  setEditingApplication(null);
                  setApplicationForm({ company: '', position: '', jobDescription: '', status: 'applied' });
                }}
              >
                <Plus className="h-4 w-4 mr-2" />
                Add Application
              </Button>
              
              <Button
                variant={currentView === 'profile' ? 'default' : 'ghost'}
                onClick={() => setCurrentView('profile')}
              >
                <User className="h-4 w-4 mr-2" />
                Profile
              </Button>
              
              <Button
                variant="ghost"
                onClick={handleLogout}
              >
                <LogOut className="h-4 w-4 mr-2" />
                Logout
              </Button>
            </nav>
          </div>
        </div>
      </header>
      
      {/* Main Content */}
      <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {currentView === 'dashboard' && (
          <div>
            <div className="flex justify-between items-center mb-6">
              <h2 className="text-2xl font-bold text-gray-900">
                Welcome back, {user.name}!
              </h2>
              <div className="text-sm text-gray-500">
                {applications.length} applications tracked
              </div>
            </div>
            
            {applications.length === 0 ? (
              <Card className="text-center py-12">
                <CardContent>
                  <Briefcase className="h-12 w-12 text-gray-400 mx-auto mb-4" />
                  <h3 className="text-lg font-medium text-gray-900 mb-2">
                    No applications yet
                  </h3>
                  <p className="text-gray-500 mb-4">
                    Start tracking your job applications and generate AI-powered documents
                  </p>
                  <Button onClick={() => setCurrentView('add-application')}>
                    <Plus className="h-4 w-4 mr-2" />
                    Add Your First Application
                  </Button>
                </CardContent>
              </Card>
            ) : (
              <div className="grid gap-6">
                {applications.map((application) => (
                  <Card key={application.id} className="hover:shadow-lg transition-shadow">
                    <CardHeader>
                      <div className="flex justify-between items-start">
                        <div>
                          <CardTitle className="text-lg">
                            {application.position} at {application.company}
                          </CardTitle>
                          <CardDescription>
                            Applied on {new Date(application.appliedDate).toLocaleDateString()}
                          </CardDescription>
                        </div>
                        <div className="flex items-center space-x-2">
                          <span className={`px-2 py-1 rounded-full text-xs font-medium ${
                            application.status === 'applied' ? 'bg-blue-100 text-blue-800' :
                            application.status === 'interview' ? 'bg-yellow-100 text-yellow-800' :
                            application.status === 'offer' ? 'bg-green-100 text-green-800' :
                            'bg-red-100 text-red-800'
                          }`}>
                            {application.status.charAt(0).toUpperCase() + application.status.slice(1)}
                          </span>
                          <Button
                            variant="ghost"
                            size="sm"
                            onClick={() => startEdit(application)}
                          >
                            <Edit className="h-4 w-4" />
                          </Button>
                          <Button
                            variant="ghost"
                            size="sm"
                            onClick={() => handleDeleteApplication(application.id)}
                          >
                            <Trash2 className="h-4 w-4" />
                          </Button>
                        </div>
                      </div>
                    </CardHeader>
                    <CardContent>
                      <div className="space-y-4">
                        <div>
                          <h4 className="font-medium text-gray-900 mb-2">Job Description</h4>
                          <p className="text-sm text-gray-600 whitespace-pre-wrap">
                            {application.jobDescription}
                          </p>
                        </div>
                        
                        <div className="flex space-x-4">
                          <Button
                            variant="outline"
                            onClick={() => generateDocument(application.id, 'resume')}
                            disabled={isGeneratingDoc && generatingDoc?.applicationId === application.id && generatingDoc?.documentType === 'resume'}
                          >
                            {isGeneratingDoc && generatingDoc?.applicationId === application.id && generatingDoc?.documentType === 'resume' ? (
                              <>
                                <div className="animate-spin rounded-full h-4 w-4 border-b-2 border-gray-600 mr-2"></div>
                                Generating Resume...
                              </>
                            ) : (
                              <>
                                <FileText className="h-4 w-4 mr-2" />
                                {application.resume ? 'Regenerate Resume' : 'Generate Resume'}
                              </>
                            )}
                          </Button>
                          
                          <Button
                            variant="outline"
                            onClick={() => generateDocument(application.id, 'coverLetter')}
                            disabled={isGeneratingDoc && generatingDoc?.applicationId === application.id && generatingDoc?.documentType === 'coverLetter'}
                          >
                            {isGeneratingDoc && generatingDoc?.applicationId === application.id && generatingDoc?.documentType === 'coverLetter' ? (
                              <>
                                <div className="animate-spin rounded-full h-4 w-4 border-b-2 border-gray-600 mr-2"></div>
                                Generating Cover Letter...
                              </>
                            ) : (
                              <>
                                <FileText className="h-4 w-4 mr-2" />
                                {application.coverLetter ? 'Regenerate Cover Letter' : 'Generate Cover Letter'}
                              </>
                            )}
                          </Button>
                        </div>
                        
                        {/* Show generated documents */}
                        {(application.resume || application.coverLetter || (isGeneratingDoc && generatingDoc?.applicationId === application.id)) && (
                          <div className="space-y-4 mt-4">
                            {(application.resume || (isGeneratingDoc && generatingDoc?.applicationId === application.id && generatingDoc?.documentType === 'resume')) && (
                              <div>
                                <h4 className="font-medium text-gray-900 mb-2">Resume</h4>
                                <div className="bg-gray-50 p-4 rounded-lg">
                                  <pre className="whitespace-pre-wrap text-sm text-gray-700">
                                    {isGeneratingDoc && generatingDoc?.applicationId === application.id && generatingDoc?.documentType === 'resume' ? 
                                      (completion || 'Generating resume...') : 
                                      application.resume
                                    }
                                  </pre>
                                </div>
                              </div>
                            )}
                            
                            {(application.coverLetter || (isGeneratingDoc && generatingDoc?.applicationId === application.id && generatingDoc?.documentType === 'coverLetter')) && (
                              <div>
                                <h4 className="font-medium text-gray-900 mb-2">Cover Letter</h4>
                                <div className="bg-gray-50 p-4 rounded-lg">
                                  <pre className="whitespace-pre-wrap text-sm text-gray-700">
                                    {isGeneratingDoc && generatingDoc?.applicationId === application.id && generatingDoc?.documentType === 'coverLetter' ? 
                                      (completion || 'Generating cover letter...') : 
                                      application.coverLetter
                                    }
                                  </pre>
                                </div>
                              </div>
                            )}
                          </div>
                        )}
                      </div>
                    </CardContent>
                  </Card>
                ))}
              </div>
            )}
          </div>
        )}
        
        {currentView === 'add-application' && (
          <div className="max-w-2xl mx-auto">
            <Card>
              <CardHeader>
                <CardTitle>
                  {editingApplication ? 'Edit Application' : 'Add New Application'}
                </CardTitle>
                <CardDescription>
                  {editingApplication ? 'Update your job application details' : 'Track a new job application'}
                </CardDescription>
              </CardHeader>
              <CardContent>
                <form onSubmit={editingApplication ? handleUpdateApplication : handleAddApplication} className="space-y-4">
                  <div>
                    <Label htmlFor="company">Company</Label>
                    <Input
                      id="company"
                      value={applicationForm.company}
                      onChange={(e) => setApplicationForm({...applicationForm, company: e.target.value})}
                      required
                    />
                  </div>
                  
                  <div>
                    <Label htmlFor="position">Position</Label>
                    <Input
                      id="position"
                      value={applicationForm.position}
                      onChange={(e) => setApplicationForm({...applicationForm, position: e.target.value})}
                      required
                    />
                  </div>
                  
                  <div>
                    <Label htmlFor="status">Status</Label>
                    <select
                      id="status"
                      value={applicationForm.status}
                      onChange={(e) => setApplicationForm({...applicationForm, status: e.target.value})}
                      className="w-full p-2 border border-gray-300 rounded-md"
                    >
                      <option value="applied">Applied</option>
                      <option value="interview">Interview</option>
                      <option value="offer">Offer</option>
                      <option value="rejected">Rejected</option>
                    </select>
                  </div>
                  
                  <div>
                    <Label htmlFor="jobDescription">Job Description</Label>
                    <Textarea
                      id="jobDescription"
                      value={applicationForm.jobDescription}
                      onChange={(e) => setApplicationForm({...applicationForm, jobDescription: e.target.value})}
                      rows={6}
                      placeholder="Paste the job description here..."
                      required
                    />
                  </div>
                  
                  <div className="flex space-x-4">
                    <Button type="submit">
                      {editingApplication ? 'Update Application' : 'Add Application'}
                    </Button>
                    <Button
                      type="button"
                      variant="outline"
                      onClick={() => {
                        setCurrentView('dashboard');
                        setEditingApplication(null);
                        setApplicationForm({ company: '', position: '', jobDescription: '', status: 'applied' });
                      }}
                    >
                      Cancel
                    </Button>
                  </div>
                </form>
              </CardContent>
            </Card>
          </div>
        )}
        
        {currentView === 'profile' && (
          <div className="max-w-2xl mx-auto">
            <Card>
              <CardHeader>
                <CardTitle>Profile</CardTitle>
                <CardDescription>
                  Complete your profile to generate better resumes and cover letters
                </CardDescription>
              </CardHeader>
              <CardContent>
                <form onSubmit={handleSaveProfile} className="space-y-6">
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <Label htmlFor="name">Full Name</Label>
                      <Input
                        id="name"
                        value={profileForm.name}
                        onChange={(e) => setProfileForm({...profileForm, name: e.target.value})}
                        required
                      />
                    </div>
                    <div>
                      <Label htmlFor="email">Email</Label>
                      <Input
                        id="email"
                        type="email"
                        value={profileForm.email}
                        onChange={(e) => setProfileForm({...profileForm, email: e.target.value})}
                        required
                      />
                    </div>
                  </div>
                  
                  <div>
                    <Label htmlFor="phone">Phone</Label>
                    <Input
                      id="phone"
                      value={profileForm.phone}
                      onChange={(e) => setProfileForm({...profileForm, phone: e.target.value})}
                    />
                  </div>
                  
                  <div>
                    <Label>Skills</Label>
                    <div className="space-y-2">
                      {profileForm.skills.map((skill, index) => (
                        <div key={index} className="flex items-center space-x-2">
                          <Input
                            value={skill}
                            onChange={(e) => updateSkill(index, e.target.value)}
                            placeholder="e.g., JavaScript, React, Project Management"
                          />
                          <Button
                            type="button"
                            variant="ghost"
                            size="sm"
                            onClick={() => removeSkill(index)}
                            disabled={profileForm.skills.length <= 1}
                          >
                            <Trash2 className="h-4 w-4" />
                          </Button>
                        </div>
                      ))}
                      <Button
                        type="button"
                        variant="outline"
                        size="sm"
                        onClick={addSkill}
                      >
                        <Plus className="h-4 w-4 mr-2" />
                        Add Skill
                      </Button>
                    </div>
                  </div>
                  
                  <div>
                    <Label>Experience</Label>
                    {profileForm.experience.map((exp, index) => (
                      <div key={index} className="p-4 border rounded-lg space-y-4 mb-4">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                          <div>
                            <Label htmlFor={`exp-title-${index}`}>Job Title</Label>
                            <Input
                              id={`exp-title-${index}`}
                              value={exp.title}
                              onChange={(e) => {
                                const newExp = [...profileForm.experience];
                                newExp[index].title = e.target.value;
                                setProfileForm({...profileForm, experience: newExp});
                              }}
                              placeholder="e.g., Software Developer"
                            />
                          </div>
                          <div>
                            <Label htmlFor={`exp-company-${index}`}>Company</Label>
                            <Input
                              id={`exp-company-${index}`}
                              value={exp.company}
                              onChange={(e) => {
                                const newExp = [...profileForm.experience];
                                newExp[index].company = e.target.value;
                                setProfileForm({...profileForm, experience: newExp});
                              }}
                              placeholder="e.g., Google"
                            />
                          </div>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                          <div>
                            <Label htmlFor={`exp-start-${index}`}>Start Date</Label>
                            <Input
                              id={`exp-start-${index}`}
                              type="date"
                              value={exp.startDate}
                              onChange={(e) => {
                                const newExp = [...profileForm.experience];
                                newExp[index].startDate = e.target.value;
                                setProfileForm({...profileForm, experience: newExp});
                              }}
                            />
                          </div>
                          <div>
                            <Label htmlFor={`exp-end-${index}`}>End Date</Label>
                            <Input
                              id={`exp-end-${index}`}
                              type="date"
                              value={exp.endDate}
                              onChange={(e) => {
                                const newExp = [...profileForm.experience];
                                newExp[index].endDate = e.target.value;
                                setProfileForm({...profileForm, experience: newExp});
                              }}
                            />
                          </div>
                        </div>
                        <div>
                          <Label htmlFor={`exp-desc-${index}`}>Description</Label>
                          <Textarea
                            id={`exp-desc-${index}`}
                            value={exp.description}
                            onChange={(e) => {
                              const newExp = [...profileForm.experience];
                              newExp[index].description = e.target.value;
                              setProfileForm({...profileForm, experience: newExp});
                            }}
                            placeholder="Describe your responsibilities and achievements..."
                            rows={3}
                          />
                        </div>
                      </div>
                    ))}
                  </div>
                  
                  <div>
                    <Label>Education</Label>
                    {profileForm.education.map((edu, index) => (
                      <div key={index} className="p-4 border rounded-lg space-y-4 mb-4">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                          <div>
                            <Label htmlFor={`edu-degree-${index}`}>Degree</Label>
                            <Input
                              id={`edu-degree-${index}`}
                              value={edu.degree}
                              onChange={(e) => {
                                const newEdu = [...profileForm.education];
                                newEdu[index].degree = e.target.value;
                                setProfileForm({...profileForm, education: newEdu});
                              }}
                              placeholder="e.g., Bachelor of Science in Computer Science"
                            />
                          </div>
                          <div>
                            <Label htmlFor={`edu-institution-${index}`}>Institution</Label>
                            <Input
                              id={`edu-institution-${index}`}
                              value={edu.institution}
                              onChange={(e) => {
                                const newEdu = [...profileForm.education];
                                newEdu[index].institution = e.target.value;
                                setProfileForm({...profileForm, education: newEdu});
                              }}
                              placeholder="e.g., Stanford University"
                            />
                          </div>
                        </div>
                        <div>
                          <Label htmlFor={`edu-grad-${index}`}>Graduation Date</Label>
                          <Input
                            id={`edu-grad-${index}`}
                            type="date"
                            value={edu.graduationDate}
                            onChange={(e) => {
                              const newEdu = [...profileForm.education];
                              newEdu[index].graduationDate = e.target.value;
                              setProfileForm({...profileForm, education: newEdu});
                            }}
                          />
                        </div>
                      </div>
                    ))}
                  </div>
                  
                  <div className="flex space-x-4">
                    <Button type="submit">
                      Save Profile
                    </Button>
                    <Button
                      type="button"
                      variant="outline"
                      onClick={() => setCurrentView('dashboard')}
                    >
                      Cancel
                    </Button>
                  </div>
                </form>
              </CardContent>
            </Card>
          </div>
        )}
      </main>
    </div>
  );
}