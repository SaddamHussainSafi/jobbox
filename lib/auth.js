import { connectDB } from './db';
import { v4 as uuidv4 } from 'uuid';
import crypto from 'crypto';

export async function hashPassword(password) {
  const hash = crypto.createHash('sha256');
  hash.update(password);
  return hash.digest('hex');
}

export async function verifyPassword(password, hashedPassword) {
  const hash = crypto.createHash('sha256');
  hash.update(password);
  return hash.digest('hex') === hashedPassword;
}

export async function createUser(email, password, name) {
  const db = await connectDB();
  const users = db.collection('users');
  
  // Check if user already exists
  const existingUser = await users.findOne({ email });
  if (existingUser) {
    throw new Error('User already exists');
  }
  
  const hashedPassword = await hashPassword(password);
  const user = {
    id: uuidv4(),
    email,
    password: hashedPassword,
    name,
    createdAt: new Date()
  };
  
  await users.insertOne(user);
  return { id: user.id, email: user.email, name: user.name };
}

export async function authenticateUser(email, password) {
  const db = await connectDB();
  const users = db.collection('users');
  
  const user = await users.findOne({ email });
  if (!user) {
    const error = new Error('User not found');
    error.status = 404;
    throw error;
  }
  
  const isValid = await verifyPassword(password, user.password);
  if (!isValid) {
    const error = new Error('Invalid password');
    error.status = 401;
    throw error;
  }
  
  return { id: user.id, email: user.email, name: user.name };
}

export async function getUserById(userId) {
  const db = await connectDB();
  const users = db.collection('users');
  
  const user = await users.findOne({ id: userId });
  if (!user) {
    return null;
  }
  
  return { id: user.id, email: user.email, name: user.name };
}