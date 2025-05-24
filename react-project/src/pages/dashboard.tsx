import { AuthGuard } from '@/components/auth/AuthGuard';
import { Layout } from '@/components/layout/Layout';
import { useAppSelector } from '@/store/hooks';

export default function Dashboard() {
  const { user } = useAppSelector((state) => state.auth);

  return (
    <AuthGuard>
      <Layout>
        <h1>Dashboard</h1>
        <p>Welcome to your dashboard, {user?.email}!</p>
        <div>
          <h2>User Details:</h2>
          <p>ID: {user?.id}</p>
          <p>Email: {user?.email}</p>
          <p>Name: {user?.name || 'Not provided'}</p>
        </div>
      </Layout>
    </AuthGuard>
  );
}