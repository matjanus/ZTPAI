import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import Navbar from '../components/Navbar';
import ErrorMessage from '../components/ErrorMessage';
import FlashcardView from '../components/FlashcardView';
import FlashcardControls from '../components/FlashcardControls';
import './FlashcardGamePage.css';

export default function FlashcardGamePage() {
  const { id } = useParams();
  const [vocab, setVocab] = useState([]);
  const [error, setError] = useState(null);
  const [index, setIndex] = useState(0);
  const [flipped, setFlipped] = useState(false);
  const [animateFlip, setAnimateFlip] = useState(false);
  const token = localStorage.getItem('token');

  useEffect(() => {
    const fetchVocab = async () => {
      try {
        const res = await fetch(`http://127.0.0.1:8000/api/quiz/${id}/vocabulary`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (res.status === 403) {
          setError({ code: 403, message: 'Access denied to this quiz.' });
        } else if (res.status === 404) {
          setError({ code: 404, message: 'Quiz not found.' });
        } else if (!res.ok) {
          throw new Error('Unexpected error');
        } else {
          const data = await res.json();
          setVocab(data);
        }
      } catch (err) {
        console.error(err);
        setError({ code: 500, message: 'Something went wrong.' });
      }
    };

    fetchVocab();
  }, [id]);

  const handleFlip = () => {
    setAnimateFlip(true);
    setFlipped((prev) => !prev);
  };

  const handlePrev = () => {
    setAnimateFlip(false);
    setFlipped(false);
    setIndex((prev) => (prev > 0 ? prev - 1 : prev));
  };

  const handleNext = () => {
    setAnimateFlip(false);
    setFlipped(false);
    setIndex((prev) => (prev < vocab.length - 1 ? prev + 1 : prev));
  };

  return (
    <div className="flashcard-game-page">
      <Navbar />
      {error ? (
        <ErrorMessage code={error.code} message={error.message} />
      ) : vocab.length > 0 ? (
        <div className="flashcard-game">
          <FlashcardView
            key={index}
            word={vocab[index].word}
            translation={vocab[index].translation}
            flipped={flipped}
            animateFlip={animateFlip}
          />
          <FlashcardControls
            onFlip={handleFlip}
            onNext={handleNext}
            onPrev={handlePrev}
          />
        </div>
      ) : (
        <p className="loading-message">Loading flashcards...</p>
      )}
    </div>
  );
}
