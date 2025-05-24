import { useEffect } from 'react';
import { useRouter } from 'next/router';
import { useAppSelector, useAppDispatch } from '@/store/hooks';
import { checkAuth } from '@/store/slices/authSlice';

export const useAuth = (requireAuth: boolean = true) => {
  const router = useRouter();
  const dispatch = useAppDispatch();
  const { isAuthenticated, isLoading, user } = useAppSelector(
    (state) => state.auth
  );

  useEffect(() => {
    const token = typeof window !== 'undefined' ? localStorage.getItem('token') : null;
    
    if (token && !isAuthenticated) {
      dispatch(checkAuth());
    } else if (!token && requireAuth) {
      router.push('/login');
    }
  }, [dispatch, isAuthenticated, requireAuth, router]);

  return {
    isAuthenticated,
    isLoading,
    user,
  };
};