import "./globals.css";

export const metadata = {
  title: "Careero - Job Application Manager",
  description: "Track your job applications and generate AI-powered resumes and cover letters",
};

export default function RootLayout({ children }) {
  return (
    <html lang="en">
      <body className="antialiased">
        <div className="min-h-screen bg-background">
          {children}
        </div>
      </body>
    </html>
  );
}