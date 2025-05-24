import { useRouter } from 'next/router';
import { useAppDispatch, useAppSelector } from '@/store/hooks';
import { logout } from '@/store/slices/authSlice';

interface LayoutProps {
  children: React.ReactNode;
}

// Common layout wrapper with header and logout functionality
export const Layout: React.FC<LayoutProps> = ({ children }) => {
  const router = useRouter();
  const dispatch = useAppDispatch();
  const { user } = useAppSelector((state) => state.auth);

  // Handles logout and redirects to login page
  const handleLogout = async () => {
    await dispatch(logout());
    router.push('/login');
  };

  return (
    <div>
      <header>
        {/* Show header only if user is logged in */}
        {user && (
          <>
            <span>Welcome, {user.email}</span>
            <button onClick={handleLogout}>Logout</button>
          </>
        )}
      </header>
      <main>{children}</main>
    </div>
  );
};
