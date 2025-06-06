import { Navigate, useLocation } from 'react-router-dom';
import { jwtDecode } from 'jwt-decode';
import { useEffect, useState } from 'react';

export default function RequireAuth({ children }) {
  const token = localStorage.getItem('token');
  const refreshToken = localStorage.getItem('refreshToken');
  const location = useLocation();
  const [isValid, setIsValid] = useState(null);

  useEffect(() => {
    const validate = async () => {
      if (!token) return setIsValid(false);

      try {
        const decoded = jwtDecode(token);
        const now = Date.now() / 1000;

        if (decoded.exp < now) {
          const res = await fetch('http://127.0.0.1:8000/api/token/refresh', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ refresh: refreshToken }),
          });

          if (!res.ok) throw new Error('Refresh failed');
          const data = await res.json();
          localStorage.setItem('token', data.access);
        }

        const res = await fetch('http://127.0.0.1:8000/api/me', {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
          },
        });

        if (!res.ok) throw new Error('Invalid token');
        setIsValid(true);
      } catch (err) {
        console.error('Auth error:', err);
        setIsValid(false);
      }
    };

    validate();
  }, [token, refreshToken]);

  if (isValid === null) return null;
  if (!isValid) return <Navigate to="/login" state={{ from: location }} replace />;

  return children;
}
