// import { useNavigate } from 'react-router-dom';
// import './QuizDetails.css';

// export default function QuizDetails({ title, quizId }) {
//   const navigate = useNavigate();

//   return (
//     <div className="quiz-details">
//       <h2>{title}</h2>
//       <button className="quiz-play-button" onClick={() => navigate(`/quiz/${quizId}/flashcards`)}>
//         Play Flashcards
//       </button>
//     </div>
//   );
// }

import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import './QuizDetails.css';

export default function QuizDetails({ title, quizId }) {
  const navigate = useNavigate();
  const [isFavourite, setIsFavourite] = useState(false);
  const token = localStorage.getItem('token');

  useEffect(() => {
    const checkFavourite = async () => {
      try {
        const res = await fetch(`http://127.0.0.1:8000/api/favourites/${quizId}/check`, {
          headers: { Authorization: `Bearer ${token}` },
        });
        if (res.ok) {
          const data = await res.json();
          setIsFavourite(data.favourite);
        }
      } catch (err) {
        console.error('Error checking favourite', err);
      }
    };

    checkFavourite();
  }, [quizId]);

  const toggleFavourite = async () => {
    try {
      if (isFavourite) {
        await fetch(`http://127.0.0.1:8000/api/favourites/${quizId}`, {
          method: 'DELETE',
          headers: { Authorization: `Bearer ${token}` },
        });
        setIsFavourite(false);
      } else {
        await fetch(`http://127.0.0.1:8000/api/favourites/${quizId}`, {
          method: 'POST',
          headers: { Authorization: `Bearer ${token}` },
        });
        setIsFavourite(true);
      }
    } catch (err) {
      console.error('Failed to toggle favourite', err);
    }
  };

  return (
    <div className="quiz-details">
      <h2>{title}</h2>
      <div className="quiz-buttons">
        <button className="quiz-button" onClick={() => navigate(`/quiz/${quizId}/flashcards`)}>
          Play Flashcards
        </button>
        <button className="quiz-button" onClick={toggleFavourite}>
          {isFavourite ? '★ Remove from favourites' : '☆ Add to favourites'}
        </button>
      </div>
    </div>
  );
}