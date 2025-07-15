import { cookies } from 'next/headers';
import { v4 as uuidv4 } from 'uuid';

const COOKIE_NAME = 'careero_session';

export async function createSession(userId) {
  const sessionId = uuidv4();
  const cookieStore = cookies();
  
  // In a real app, you'd store sessions in a database
  // For this MVP, we'll use a simple approach
  const sessionData = {
    userId,
    sessionId,
    createdAt: new Date()
  };
  
  cookieStore.set(COOKIE_NAME, JSON.stringify(sessionData), {
    httpOnly: true,
    secure: process.env.NODE_ENV === 'production',
    sameSite: 'lax',
    maxAge: 60 * 60 * 24 * 7 // 7 days
  });
  
  return sessionId;
}

export async function getSession() {
  const cookieStore = cookies();
  const sessionCookie = cookieStore.get(COOKIE_NAME);
  
  if (!sessionCookie) {
    return null;
  }
  
  try {
    const sessionData = JSON.parse(sessionCookie.value);
    return sessionData;
  } catch (error) {
    return null;
  }
}

export async function destroySession() {
  const cookieStore = cookies();
  cookieStore.delete(COOKIE_NAME);
}