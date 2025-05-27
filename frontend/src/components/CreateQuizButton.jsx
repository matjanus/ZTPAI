import { useNavigate } from 'react-router-dom';
import './CreateQuizButton.css';

export default function CreateQuizButton() {
  const navigate = useNavigate();

  return (
    <button className="create-button" onClick={() => navigate('/create_quiz')}>
      Create new quiz
    </button>
  );
}
