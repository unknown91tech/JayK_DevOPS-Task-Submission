import { useEffect } from 'react';
import { useRouter } from 'next/router';
import { useAuth } from '@/hooks/useAuth';

export default function Home() {
  const router = useRouter();
  const { isAuthenticated } = useAuth(false);

  useEffect(() => {
    if (isAuthenticated) {
      router.push('/dashboard');
    } else {
      router.push('/login');
    }
  }, [isAuthenticated, router]);

  return <div>Redirecting...</div>;
}