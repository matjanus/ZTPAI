import Navbar from '../components/Navbar';
import RecentQuizzes from '../components/lists/RecentQuizzes';
import "./MainPage.css";



export default function MainPage() {
  return (
    <div className="main-page">
        <Navbar />
        <RecentQuizzes />
    </div>
  );
}