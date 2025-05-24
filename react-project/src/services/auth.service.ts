import api from './api';
import { AuthResponse, LoginCredentials, RegisterCredentials } from '@/types/auth';
import { User } from '@/types/user';

export const authService = {
  async login(credentials: LoginCredentials): Promise<AuthResponse> {
    const response = await api.post<AuthResponse>('/auth/login', credentials);
    return response.data;
  },

  async register(credentials: RegisterCredentials): Promise<AuthResponse> {
    const response = await api.post<AuthResponse>('/auth/register', credentials);
    return response.data;
  },

  async getMe(token: string): Promise<User> {
    const response = await api.get<User>('/auth/me', {
      headers: { Authorization: `Bearer ${token}` },
    });
    return response.data;
  },

  async logout(): Promise<void> {
    await api.post('/auth/logout');
  },
};