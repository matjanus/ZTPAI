import Navbar from '../components/Navbar';
import RecentQuizzes from '../components/RecentQuizzes';
import "./MainPage.css";


export default function MainPage() {
  return (
    <div class="main-page">
        <Navbar />
        <RecentQuizzes />
    </div>
  );
}