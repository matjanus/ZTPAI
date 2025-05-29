import { useRef } from 'react';

import Navbar from '../components/Navbar';
import FavouriteQuizzesList from '../components/lists/FavouriteQuizzesList';
import useDynamicBodyHeight from '../hooks/useDynamicBodyHeight';
import "./FavouriteQuizzesPage.css";



export default function FavouriteQuizzesPage() {

  const navbarRef = useRef(null);
  const bodyRef = useRef(null);

  useDynamicBodyHeight(navbarRef, bodyRef);

  return (
    <div className="favourite-quizzes-page">
        <div ref={navbarRef}>
          <Navbar />
        </div>
        <div className="favourite-quizzes" ref={bodyRef}>
            <FavouriteQuizzesList />
        </div>
    </div>
  );
}