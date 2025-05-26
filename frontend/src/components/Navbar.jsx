import { useNavigate } from 'react-router-dom';
import './Navbar.css';

export default function Navbar() {
  const navigate = useNavigate();

  const handleLogout = () => {
    localStorage.removeItem('token');
    navigate('/login');
  };

  return (
    <nav className="navbar">
      <button onClick={() => navigate('/')}>Bezili</button>
      <button onClick={() => navigate('/quizzes')}>Quizzes</button>
      <button onClick={() => navigate('/profile')}>Profile</button>
      <button onClick={handleLogout}>LogOut</button>
    </nav>
  );
}