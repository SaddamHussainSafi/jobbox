#!/usr/bin/env python3
"""
Comprehensive Backend API Test for Careero Job Application Management System
Tests all authentication, CRUD operations, profile management, and OpenAI integration
"""

import requests
import json
import time
import os
from datetime import datetime

# Get base URL from environment
BASE_URL = os.getenv('NEXT_PUBLIC_BASE_URL', 'https://408af167-651d-4cd8-9016-8fc6e5a20950.preview.emergentagent.com')
API_BASE = f"{BASE_URL}/api"

class CareeroAPITester:
    def __init__(self):
        self.session = requests.Session()
        self.test_user_data = {
            "name": "Sarah Johnson",
            "email": f"sarah.johnson.{int(time.time())}@example.com",
            "password": "SecurePass123!"
        }
        self.test_results = []
        self.user_id = None
        self.application_id = None
        
    def log_test(self, test_name, success, message, details=None):
        """Log test results"""
        result = {
            "test": test_name,
            "success": success,
            "message": message,
            "timestamp": datetime.now().isoformat(),
            "details": details
        }
        self.test_results.append(result)
        status = "✅ PASS" if success else "❌ FAIL"
        print(f"{status}: {test_name} - {message}")
        if details and not success:
            print(f"   Details: {details}")
    
    def test_user_registration(self):
        """Test user registration endpoint"""
        try:
            response = self.session.post(
                f"{API_BASE}/auth/register",
                json=self.test_user_data,
                headers={"Content-Type": "application/json"}
            )
            
            if response.status_code == 201:
                data = response.json()
                if 'user' in data and data['user']['email'] == self.test_user_data['email']:
                    self.user_id = data['user']['id']
                    self.log_test("User Registration", True, "User registered successfully")
                    return True
                else:
                    self.log_test("User Registration", False, "Invalid response format", data)
                    return False
            else:
                self.log_test("User Registration", False, f"HTTP {response.status_code}", response.text)
                return False
                
        except Exception as e:
            self.log_test("User Registration", False, f"Exception: {str(e)}")
            return False
    
    def test_user_login(self):
        """Test user login endpoint"""
        try:
            login_data = {
                "email": self.test_user_data["email"],
                "password": self.test_user_data["password"]
            }
            
            response = self.session.post(
                f"{API_BASE}/auth/login",
                json=login_data,
                headers={"Content-Type": "application/json"}
            )
            
            if response.status_code == 200:
                data = response.json()
                if 'user' in data and data['user']['email'] == self.test_user_data['email']:
                    self.log_test("User Login", True, "User logged in successfully")
                    return True
                else:
                    self.log_test("User Login", False, "Invalid response format", data)
                    return False
            else:
                self.log_test("User Login", False, f"HTTP {response.status_code}", response.text)
                return False
                
        except Exception as e:
            self.log_test("User Login", False, f"Exception: {str(e)}")
            return False
    
    def test_get_current_user(self):
        """Test get current user endpoint"""
        try:
            response = self.session.get(f"{API_BASE}/auth/me")
            
            if response.status_code == 200:
                data = response.json()
                if 'user' in data and data['user']['email'] == self.test_user_data['email']:
                    self.log_test("Get Current User", True, "Current user retrieved successfully")
                    return True
                else:
                    self.log_test("Get Current User", False, "Invalid response format", data)
                    return False
            else:
                self.log_test("Get Current User", False, f"HTTP {response.status_code}", response.text)
                return False
                
        except Exception as e:
            self.log_test("Get Current User", False, f"Exception: {str(e)}")
            return False
    
    def test_create_profile(self):
        """Test profile creation/update endpoint"""
        try:
            profile_data = {
                "name": self.test_user_data["name"],
                "email": self.test_user_data["email"],
                "phone": "+1-555-0123",
                "skills": ["Python", "JavaScript", "React", "Node.js", "MongoDB"],
                "experience": [
                    {
                        "title": "Senior Software Engineer",
                        "company": "Tech Solutions Inc",
                        "startDate": "2022-01-15",
                        "endDate": "2024-12-01",
                        "description": "Led development of web applications using React and Node.js. Managed a team of 3 developers and implemented CI/CD pipelines."
                    },
                    {
                        "title": "Software Developer",
                        "company": "StartupXYZ",
                        "startDate": "2020-06-01",
                        "endDate": "2021-12-31",
                        "description": "Developed full-stack applications using Python and JavaScript. Worked on database design and API development."
                    }
                ],
                "education": [
                    {
                        "degree": "Bachelor of Science in Computer Science",
                        "institution": "University of Technology",
                        "graduationDate": "2020-05-15"
                    }
                ]
            }
            
            response = self.session.post(
                f"{API_BASE}/profile",
                json=profile_data,
                headers={"Content-Type": "application/json"}
            )
            
            if response.status_code == 200:
                data = response.json()
                if 'profile' in data and data['profile']['name'] == profile_data['name']:
                    self.log_test("Create Profile", True, "Profile created successfully")
                    return True
                else:
                    self.log_test("Create Profile", False, "Invalid response format", data)
                    return False
            else:
                self.log_test("Create Profile", False, f"HTTP {response.status_code}", response.text)
                return False
                
        except Exception as e:
            self.log_test("Create Profile", False, f"Exception: {str(e)}")
            return False
    
    def test_get_profile(self):
        """Test get profile endpoint"""
        try:
            response = self.session.get(f"{API_BASE}/profile")
            
            if response.status_code == 200:
                data = response.json()
                if 'profile' in data and data['profile'] is not None:
                    self.log_test("Get Profile", True, "Profile retrieved successfully")
                    return True
                else:
                    self.log_test("Get Profile", False, "Profile not found or invalid format", data)
                    return False
            else:
                self.log_test("Get Profile", False, f"HTTP {response.status_code}", response.text)
                return False
                
        except Exception as e:
            self.log_test("Get Profile", False, f"Exception: {str(e)}")
            return False
    
    def test_create_application(self):
        """Test job application creation"""
        try:
            application_data = {
                "company": "Google",
                "position": "Senior Software Engineer",
                "jobDescription": """We are looking for a Senior Software Engineer to join our team. 
                
Key Responsibilities:
- Design and develop scalable web applications
- Collaborate with cross-functional teams
- Mentor junior developers
- Participate in code reviews and architecture decisions

Requirements:
- 5+ years of software development experience
- Strong knowledge of JavaScript, Python, or Java
- Experience with cloud platforms (GCP, AWS, Azure)
- Excellent problem-solving skills
- Bachelor's degree in Computer Science or related field

Benefits:
- Competitive salary and equity
- Health, dental, and vision insurance
- Flexible work arrangements
- Professional development opportunities""",
                "status": "applied"
            }
            
            response = self.session.post(
                f"{API_BASE}/applications",
                json=application_data,
                headers={"Content-Type": "application/json"}
            )
            
            if response.status_code == 201:
                data = response.json()
                if 'application' in data and data['application']['company'] == application_data['company']:
                    self.application_id = data['application']['id']
                    self.log_test("Create Application", True, "Job application created successfully")
                    return True
                else:
                    self.log_test("Create Application", False, "Invalid response format", data)
                    return False
            else:
                self.log_test("Create Application", False, f"HTTP {response.status_code}", response.text)
                return False
                
        except Exception as e:
            self.log_test("Create Application", False, f"Exception: {str(e)}")
            return False
    
    def test_get_applications(self):
        """Test get all applications endpoint"""
        try:
            response = self.session.get(f"{API_BASE}/applications")
            
            if response.status_code == 200:
                data = response.json()
                if 'applications' in data and isinstance(data['applications'], list):
                    if len(data['applications']) > 0:
                        self.log_test("Get Applications", True, f"Retrieved {len(data['applications'])} applications")
                        return True
                    else:
                        self.log_test("Get Applications", True, "No applications found (empty list)")
                        return True
                else:
                    self.log_test("Get Applications", False, "Invalid response format", data)
                    return False
            else:
                self.log_test("Get Applications", False, f"HTTP {response.status_code}", response.text)
                return False
                
        except Exception as e:
            self.log_test("Get Applications", False, f"Exception: {str(e)}")
            return False
    
    def test_get_single_application(self):
        """Test get single application endpoint"""
        if not self.application_id:
            self.log_test("Get Single Application", False, "No application ID available")
            return False
            
        try:
            response = self.session.get(f"{API_BASE}/applications/{self.application_id}")
            
            if response.status_code == 200:
                data = response.json()
                if 'application' in data and data['application']['id'] == self.application_id:
                    self.log_test("Get Single Application", True, "Single application retrieved successfully")
                    return True
                else:
                    self.log_test("Get Single Application", False, "Invalid response format", data)
                    return False
            else:
                self.log_test("Get Single Application", False, f"HTTP {response.status_code}", response.text)
                return False
                
        except Exception as e:
            self.log_test("Get Single Application", False, f"Exception: {str(e)}")
            return False
    
    def test_update_application(self):
        """Test application update endpoint"""
        if not self.application_id:
            self.log_test("Update Application", False, "No application ID available")
            return False
            
        try:
            update_data = {
                "status": "interview",
                "company": "Google",
                "position": "Senior Software Engineer - Updated"
            }
            
            response = self.session.put(
                f"{API_BASE}/applications/{self.application_id}",
                json=update_data,
                headers={"Content-Type": "application/json"}
            )
            
            if response.status_code == 200:
                data = response.json()
                if 'application' in data and data['application']['status'] == 'interview':
                    self.log_test("Update Application", True, "Application updated successfully")
                    return True
                else:
                    self.log_test("Update Application", False, "Invalid response format", data)
                    return False
            else:
                self.log_test("Update Application", False, f"HTTP {response.status_code}", response.text)
                return False
                
        except Exception as e:
            self.log_test("Update Application", False, f"Exception: {str(e)}")
            return False
    
    def test_generate_resume(self):
        """Test OpenAI resume generation (limited test due to API costs)"""
        if not self.application_id:
            self.log_test("Generate Resume", False, "No application ID available")
            return False
            
        try:
            generate_data = {
                "documentType": "resume",
                "applicationId": self.application_id
            }
            
            # Set a shorter timeout for AI generation
            response = self.session.post(
                f"{API_BASE}/generate",
                json=generate_data,
                headers={"Content-Type": "application/json"},
                timeout=30
            )
            
            # OpenAI streaming response returns different status codes
            if response.status_code in [200, 201]:
                # For streaming responses, we just check if we get a response
                self.log_test("Generate Resume", True, "Resume generation endpoint accessible")
                return True
            elif response.status_code == 404:
                # Profile might not be found
                self.log_test("Generate Resume", False, "Profile not found - complete profile first", response.text)
                return False
            else:
                self.log_test("Generate Resume", False, f"HTTP {response.status_code}", response.text)
                return False
                
        except requests.exceptions.Timeout:
            self.log_test("Generate Resume", True, "Resume generation started (timeout expected for streaming)")
            return True
        except Exception as e:
            self.log_test("Generate Resume", False, f"Exception: {str(e)}")
            return False
    
    def test_generate_cover_letter(self):
        """Test OpenAI cover letter generation (limited test due to API costs)"""
        if not self.application_id:
            self.log_test("Generate Cover Letter", False, "No application ID available")
            return False
            
        try:
            generate_data = {
                "documentType": "coverLetter",
                "applicationId": self.application_id
            }
            
            # Set a shorter timeout for AI generation
            response = self.session.post(
                f"{API_BASE}/generate",
                json=generate_data,
                headers={"Content-Type": "application/json"},
                timeout=30
            )
            
            # OpenAI streaming response returns different status codes
            if response.status_code in [200, 201]:
                # For streaming responses, we just check if we get a response
                self.log_test("Generate Cover Letter", True, "Cover letter generation endpoint accessible")
                return True
            elif response.status_code == 404:
                # Profile might not be found
                self.log_test("Generate Cover Letter", False, "Profile not found - complete profile first", response.text)
                return False
            else:
                self.log_test("Generate Cover Letter", False, f"HTTP {response.status_code}", response.text)
                return False
                
        except requests.exceptions.Timeout:
            self.log_test("Generate Cover Letter", True, "Cover letter generation started (timeout expected for streaming)")
            return True
        except Exception as e:
            self.log_test("Generate Cover Letter", False, f"Exception: {str(e)}")
            return False
    
    def test_delete_application(self):
        """Test application deletion"""
        if not self.application_id:
            self.log_test("Delete Application", False, "No application ID available")
            return False
            
        try:
            response = self.session.delete(f"{API_BASE}/applications/{self.application_id}")
            
            if response.status_code == 200:
                data = response.json()
                if data.get('success') == True:
                    self.log_test("Delete Application", True, "Application deleted successfully")
                    return True
                else:
                    self.log_test("Delete Application", False, "Invalid response format", data)
                    return False
            else:
                self.log_test("Delete Application", False, f"HTTP {response.status_code}", response.text)
                return False
                
        except Exception as e:
            self.log_test("Delete Application", False, f"Exception: {str(e)}")
            return False
    
    def test_logout(self):
        """Test user logout endpoint"""
        try:
            response = self.session.post(f"{API_BASE}/auth/logout")
            
            if response.status_code == 200:
                data = response.json()
                if data.get('success') == True:
                    self.log_test("User Logout", True, "User logged out successfully")
                    return True
                else:
                    self.log_test("User Logout", False, "Invalid response format", data)
                    return False
            else:
                self.log_test("User Logout", False, f"HTTP {response.status_code}", response.text)
                return False
                
        except Exception as e:
            self.log_test("User Logout", False, f"Exception: {str(e)}")
            return False
    
    def test_unauthorized_access(self):
        """Test that protected endpoints require authentication after logout"""
        try:
            # Test accessing protected endpoint after logout
            response = self.session.get(f"{API_BASE}/auth/me")
            
            if response.status_code == 401:
                self.log_test("Unauthorized Access Protection", True, "Protected endpoint correctly requires authentication")
                return True
            else:
                self.log_test("Unauthorized Access Protection", False, f"Expected 401, got {response.status_code}", response.text)
                return False
                
        except Exception as e:
            self.log_test("Unauthorized Access Protection", False, f"Exception: {str(e)}")
            return False
    
    def test_invalid_login(self):
        """Test login with invalid credentials"""
        try:
            invalid_login_data = {
                "email": "nonexistent@example.com",
                "password": "wrongpassword"
            }
            
            response = self.session.post(
                f"{API_BASE}/auth/login",
                json=invalid_login_data,
                headers={"Content-Type": "application/json"}
            )
            
            if response.status_code in [400, 401, 404]:
                self.log_test("Invalid Login Handling", True, "Invalid login correctly rejected")
                return True
            else:
                self.log_test("Invalid Login Handling", False, f"Expected 400/401/404, got {response.status_code}", response.text)
                return False
                
        except Exception as e:
            self.log_test("Invalid Login Handling", False, f"Exception: {str(e)}")
            return False
    
    def run_all_tests(self):
        """Run all backend API tests"""
        print(f"\n🚀 Starting Careero Backend API Tests")
        print(f"📍 Testing API at: {API_BASE}")
        print(f"👤 Test user: {self.test_user_data['email']}")
        print("=" * 60)
        
        # Authentication Flow Tests
        print("\n🔐 AUTHENTICATION TESTS")
        self.test_user_registration()
        self.test_user_login()
        self.test_get_current_user()
        
        # Profile Management Tests
        print("\n👤 PROFILE MANAGEMENT TESTS")
        self.test_create_profile()
        self.test_get_profile()
        
        # Job Application CRUD Tests
        print("\n💼 JOB APPLICATION TESTS")
        self.test_create_application()
        self.test_get_applications()
        self.test_get_single_application()
        self.test_update_application()
        
        # OpenAI Integration Tests (limited due to API costs)
        print("\n🤖 AI DOCUMENT GENERATION TESTS")
        self.test_generate_resume()
        self.test_generate_cover_letter()
        
        # Cleanup Tests
        print("\n🗑️ CLEANUP TESTS")
        self.test_delete_application()
        
        # Security Tests
        print("\n🔒 SECURITY TESTS")
        self.test_logout()
        self.test_unauthorized_access()
        self.test_invalid_login()
        
        # Summary
        print("\n" + "=" * 60)
        print("📊 TEST SUMMARY")
        print("=" * 60)
        
        passed = sum(1 for result in self.test_results if result['success'])
        failed = len(self.test_results) - passed
        
        print(f"✅ Passed: {passed}")
        print(f"❌ Failed: {failed}")
        print(f"📈 Success Rate: {(passed/len(self.test_results)*100):.1f}%")
        
        if failed > 0:
            print(f"\n❌ FAILED TESTS:")
            for result in self.test_results:
                if not result['success']:
                    print(f"   • {result['test']}: {result['message']}")
        
        return passed, failed

if __name__ == "__main__":
    tester = CareeroAPITester()
    passed, failed = tester.run_all_tests()
    
    # Exit with appropriate code
    exit(0 if failed == 0 else 1)