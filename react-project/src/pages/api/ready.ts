import { NextApiRequest, NextApiResponse } from 'next';

export default function handler(
  req: NextApiRequest,
  res: NextApiResponse
) {
  if (req.method !== 'GET') {
    return res.status(405).json({ error: 'Method not allowed' });
  }

  // Simple readiness check
  res.status(200).json({
    status: 'ready',
    timestamp: new Date().toISOString(),
  });
}