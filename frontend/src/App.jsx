import { BrowserRouter as Router, Routes, Route } from "react-router-dom";;

import LoginPage from "./pages/LoginPage";
import RegisterPage from "./pages/RegisterPage";
import RequireAuth from './components/RequireAuth';

function App() {
  return (
    <Router>
      <Routes>
        {/* <Route path="/" element={<LoginPage />} /> */}
        <Route path="/login" element={<LoginPage />} />
        <Route path="/register" element={<RegisterPage />} />

        <Route
          path="/"
          element={
            <RequireAuth>
              <RegisterPage />
            </RequireAuth>
          }
        />

      </Routes>
    </Router>
  );
}

export default App;