import { useEffect } from 'react';
import { useRouter } from 'next/router';
import { useAuth } from '@/hooks/useAuth';

interface AuthGuardProps {
  children: React.ReactNode;
}

// Protects routes by checking authentication status
export const AuthGuard: React.FC<AuthGuardProps> = ({ children }) => {
  const router = useRouter();
  const { isAuthenticated, isLoading } = useAuth();

  useEffect(() => {
    // Redirect to login if user is not authenticated and loading is complete
    if (!isLoading && !isAuthenticated) {
      router.push('/login');
    }
  }, [isAuthenticated, isLoading, router]);

  // Show loading indicator while auth state is being determined
  if (isLoading) {
    return <div>Loading...</div>;
  }

  // Prevent rendering children until auth check passes
  if (!isAuthenticated) {
    return null;
  }

  // Render protected content
  return <>{children}</>;
};
