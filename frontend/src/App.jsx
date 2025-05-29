import { BrowserRouter as Router, Routes, Route } from "react-router-dom";;

import LoginPage from "./pages/LoginPage";
import MainPage from "./pages/MainPage";
import RegisterPage from "./pages/RegisterPage";
import NotFoundPage from "./pages/NotFoundPage";
import RequireAuth from './components/RequireAuth';
import UserProfilePage from "./pages/UserProfilePage";
import MyQuizzesPage from  "./pages/MyQuizzesPage";
import AddQuizPage from  "./pages/AddQuizPage";
import UserSettingsPage from  "./pages/UserSettingsPage";
import QuizPage from  "./pages/QuizPage";


UserSettingsPage
function App() {
  return (
    <Router>
      <Routes>

        <Route path="/login" element={<LoginPage />} />
        <Route path="/register" element={<RegisterPage />} />
        <Route path="/" element={<RequireAuth><MainPage /></RequireAuth>}/>
        <Route path="/user/:id" element={<RequireAuth><UserProfilePage /></RequireAuth>}/>
        <Route path="/quiz/:id" element={<RequireAuth><QuizPage /></RequireAuth>}/>
        <Route path="/quizzes" element={<RequireAuth><MyQuizzesPage /></RequireAuth>}/>
        <Route path="/create_quiz" element={<RequireAuth><AddQuizPage /></RequireAuth>}/>
        <Route path="/profile" element={<RequireAuth><UserSettingsPage /></RequireAuth>}/>
        <Route path="*" element={<RequireAuth><NotFoundPage /></RequireAuth>} />

      </Routes>
    </Router>
  );
}

export default App;