import { Navigate, useLocation } from 'react-router-dom';
import { jwtDecode } from 'jwt-decode';
import { useEffect, useState } from 'react';

export default function RequireAuth({ children }) {
  const token = localStorage.getItem('token'); // lub sessionStorage
  const location = useLocation();
  const [isValid, setIsValid] = useState(null); // null = sprawdzanie

  useEffect(() => {
    if (!token) {
      setIsValid(false);
      return;
    }

    try {
      const decoded = jwtDecode(token);
      const now = Date.now() / 1000;

      if (decoded.exp < now) {
        setIsValid(false);
        return;
      }

      fetch('http://127.0.0.1:8000/api/me', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
        .then((res) => {
          if (!res.ok) throw new Error('Token invalid');
          return res.json();
        })
        .then(() => setIsValid(true))
        .catch(() => setIsValid(false));
    } catch (err) {
      setIsValid(false);
    }
  }, [token]);

  if (isValid === null) {
    return <div>Sprawdzanie tokenu...</div>;
  }

  if (!isValid) {
    return <Navigate to="/login" state={{ from: location }} replace />;
  }

  return children;
}

