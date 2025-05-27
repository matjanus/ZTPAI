import Navbar from '../components/Navbar';
import CreateQuizButton from '../components/CreateQuizButton';
import MyCourseList from '../components/lists/MyCourseList';
import "./MyQuizzesPage.css";

export default function MyQuizzesPage() {
  return (
    <div className="my-courses-page">
      <Navbar />
      <div className="create-quiz">
        <CreateQuizButton />
      </div>
      <MyCourseList />
    </div>
  );
}
