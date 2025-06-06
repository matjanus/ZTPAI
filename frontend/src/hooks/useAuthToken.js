import { useEffect } from 'react';
import { jwtDecode } from 'jwt-decode';
import { useNavigate } from 'react-router-dom';

export default function useAuthToken() {
  const navigate = useNavigate();

  useEffect(() => {
    const interval = setInterval(() => {
      const token = localStorage.getItem('token');
      const refresh = localStorage.getItem('refreshToken');
      console.log(1);
      if (!token || !refresh) return;
      console.log(2);
      console.log(token);
      try {
        const decoded = jwtDecode(token);
        const now = Date.now() / 1000;
        const timeLeft = decoded.exp - now;

        if (timeLeft < 180) {
          fetch('http://127.0.0.1:8000/api/token/refresh', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ refresh }),
          })
            .then(res => {
              if (!res.ok) throw new Error('Refresh failed');
              return res.json();
            })
            .then(data => {
              localStorage.setItem('token', data.access);
            })
            .catch(() => {
              localStorage.removeItem('token');
              localStorage.removeItem('refreshToken');
              navigate('/login?expired=true', { replace: true });
            });
        }
      } catch (err) {
        console.error('Token decode error', err);
      }
    }, 60 * 1000);

    return () => clearInterval(interval);
  }, [navigate]);
}
