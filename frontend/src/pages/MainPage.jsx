import { useRef } from 'react';

import Navbar from '../components/Navbar';
import RecentQuizzes from '../components/lists/RecentQuizzes';
import useDynamicBodyHeight from '../hooks/useDynamicBodyHeight';
import "./MainPage.css";



export default function MainPage() {

  const navbarRef = useRef(null);
  const bodyRef = useRef(null);

  useDynamicBodyHeight(navbarRef, bodyRef);

  return (
    <div className="main-page">
        <div ref={navbarRef}>
          <Navbar />
        </div>
        <div className="recent-quizzes" ref={bodyRef}>
            <RecentQuizzes />
        </div>
    </div>
  );
}