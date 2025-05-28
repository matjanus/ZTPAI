import { useRef } from 'react';

import Navbar from '../components/Navbar';
import CreateQuizButton from '../components/CreateQuizButton';
import MyQuizzesList from '../components/lists/MyQuizzesList';
import useDynamicBodyHeight from '../hooks/useDynamicBodyHeight';
import "./MyQuizzesPage.css";

export default function MyQuizzesPage() {
  const navbarRef = useRef(null);
  const bodyRef = useRef(null);

  useDynamicBodyHeight(navbarRef, bodyRef);

  return (
    <div className="my-courses-page">
      <div ref={navbarRef}>
        <Navbar />
      </div>
      <div className="myquizzes-body" ref={bodyRef}>
        <div className="create-quiz">
          <CreateQuizButton />
        </div>
        <MyQuizzesList />
      </div>
    </div>
  );
}