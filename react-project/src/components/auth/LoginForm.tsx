import { FormEvent, useState } from 'react';
import { useRouter } from 'next/router';
import { useAppDispatch, useAppSelector } from '@/store/hooks';
import { login, register } from '@/store/slices/authSlice';

// Handles login and registration in a single form
export const LoginForm: React.FC = () => {
  const router = useRouter();
  const dispatch = useAppDispatch();
  const { isLoading, error } = useAppSelector((state) => state.auth);

  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [name, setName] = useState('');
  const [isRegister, setIsRegister] = useState(false); // Toggles between login and register mode

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();

    try {
      // Dispatch register or login based on current mode
      if (isRegister) {
        await dispatch(register({ email, password, name })).unwrap();
      } else {
        await dispatch(login({ email, password })).unwrap();
      }

      // Redirect to dashboard on success
      router.push('/dashboard');
    } catch (err) {
      console.error('Auth error:', err);
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      {/* Name field only shown during registration */}
      {isRegister && (
        <input
          type="text"
          placeholder="Name"
          value={name}
          onChange={(e) => setName(e.target.value)}
        />
      )}
      <input
        type="email"
        placeholder="Email"
        value={email}
        onChange={(e) => setEmail(e.target.value)}
        required
      />
      <input
        type="password"
        placeholder="Password"
        value={password}
        onChange={(e) => setPassword(e.target.value)}
        required
      />
      {error && <p>{error}</p>}
      <button type="submit" disabled={isLoading}>
        {isLoading ? 'Loading...' : isRegister ? 'Register' : 'Login'}
      </button>
      <button
        type="button"
        onClick={() => setIsRegister(!isRegister)}
      >
        {isRegister ? 'Already have an account? Login' : 'Need an account? Register'}
      </button>
    </form>
  );
};
