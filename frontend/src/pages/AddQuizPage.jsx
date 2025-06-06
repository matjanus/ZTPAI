import { useRef } from 'react';
import Navbar from '../components/Navbar';
import QuizForm from '../components/forms/QuizForm';
import useDynamicBodyHeight from '../hooks/useDynamicBodyHeight';
import './AddQuizPage.css';

export default function AddQuizPage() {

  const navbarRef = useRef(null);
  const bodyRef = useRef(null);

  useDynamicBodyHeight(navbarRef, bodyRef);

  return (
    <div className='add-quiz-page'>
      <div  ref={navbarRef}>
          <Navbar />
      </div>
      <div className="quiz-form-wrapper" ref={bodyRef}>
        <QuizForm />
      </div>
    </div>
  );
}