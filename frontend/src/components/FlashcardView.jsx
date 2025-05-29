import './FlashcardView.css';

export default function FlashcardView({ word, translation, flipped, animateFlip}) {
  return (
    <div className="flashcard">
      <div className={`card ${flipped ? 'flipped' : ''} ${!animateFlip ? 'no-animation' : ''}`}>
        <div className="front">{word}</div>
        <div className="back">{translation}</div>
      </div>
    </div>
  );
}
