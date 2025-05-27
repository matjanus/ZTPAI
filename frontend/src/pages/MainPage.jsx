import Navbar from '../components/Navbar';
import RecentQuizzes from '../components/lists/RecentQuizzes';
import "./MainPage.css";



export default function MainPage() {
  return (
    <div className="main-page">
        <Navbar />
        <div className="recent-quizzes">
            <RecentQuizzes />
        </div>
    </div>
  );
}