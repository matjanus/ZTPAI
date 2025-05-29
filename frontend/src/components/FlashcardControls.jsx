import { ArrowLeft, ArrowRight, RefreshCcw } from 'lucide-react';
import './FlashcardControls.css';

export default function FlashcardControls({ onFlip, onNext, onPrev }) {
  return (
    <div className="flashcard-controls">
      <button className="flashcard-button" onClick={onPrev}>&lt;</button>
      <button className="flashcard-button" onClick={onFlip}>Flip</button>
      <button className="flashcard-button" onClick={onNext}>&gt;</button>
    </div>
  );
}
