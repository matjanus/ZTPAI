import Navbar from '../components/Navbar';
import QuizForm from '../components/QuizForm';
import './AddQuizPage.css';

export default function AddQuizPage() {
  return (
    <div className='add-quiz-page' >
      <Navbar />
      <div className="quiz-form-wrapper">
        <QuizForm />
      </div>
    </div>
  );
}