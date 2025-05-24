import { useEffect } from 'react';
import { useRouter } from 'next/router';
import { LoginForm } from '@/components/auth/LoginForm';
import { useAuth } from '@/hooks/useAuth';

export default function RegisterPage() {
  const router = useRouter();
  const { isAuthenticated } = useAuth(false);

  useEffect(() => {
    if (isAuthenticated) {
      router.push('/dashboard');
    }
  }, [isAuthenticated, router]);

  return (
    <div>
      <h1>Register</h1>
      <LoginForm />
    </div>
  );
}