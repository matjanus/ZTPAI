import Navbar from '../components/Navbar';
import CreateQuizButton from '../components/CreateQuizButton';
import MyQuizzesList from '../components/lists/MyQuizzesList';
import "./MyQuizzesPage.css";

export default function MyQuizzesPage() {
  return (
    <div className="my-courses-page">
      <Navbar />
      <div className="create-quiz">
        <CreateQuizButton />
      </div>
      <MyQuizzesList />
    </div>
  );
}
