import { useState, useCallback } from 'react';
import api from '@/services/api';

interface UseApiOptions {
  onSuccess?: (data: any) => void;
  onError?: (error: any) => void;
}

export const useApi = <T = any>(options?: UseApiOptions) => {
  const [data, setData] = useState<T | null>(null);
  const [error, setError] = useState<any>(null);
  const [loading, setLoading] = useState(false);

  const execute = useCallback(
    async (method: string, url: string, payload?: any) => {
      try {
        setLoading(true);
        setError(null);
        
        const response = await api.request<T>({
          method,
          url,
          data: payload,
        });
        
        setData(response.data);
        options?.onSuccess?.(response.data);
        
        return response.data;
      } catch (err) {
        setError(err);
        options?.onError?.(err);
        throw err;
      } finally {
        setLoading(false);
      }
    },
    [options]
  );

  return {
    data,
    error,
    loading,
    execute,
  };
};