import { useState } from 'react';
import './Form.css';

export default function QuizForm() {
  const [title, setTitle] = useState('');
  const [access, setAccess] = useState('Public');
  const [pairs, setPairs] = useState([{ question: '', answer: '' }]);

  const handlePairChange = (index, field, value) => {
    const updated = [...pairs];
    updated[index][field] = value;
    setPairs(updated);

    if (
      index === pairs.length - 1 &&
      updated[index].question.trim() &&
      updated[index].answer.trim() &&
      pairs.length < 20
    ) {
      setPairs([...updated, { question: '', answer: '' }]);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    const cleanedPairs = pairs.filter(
      (pair) => pair.question.trim() && pair.answer.trim()
    );

    if (!title.trim() || cleanedPairs.length === 0) {
      alert('Enter the title and at least one correct pair of words.');
      return;
    }

    const payload = {
      title,
      access,
      vocabulary: cleanedPairs.map(({ question, answer }) => ({
        word: question,
        translation: answer,
      })),
    };

    try {
      const token = localStorage.getItem('token');

      const res = await fetch('http://127.0.0.1:8000/api/create_quiz', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify(payload),
      });

      if (!res.ok) {
        throw new Error('Error while saving the quiz.');
      }

      const result = await res.json();
      alert(`Quiz "${result.title}" was added!`);
    } catch (err) {
      console.error(err);
      alert('Failed to add quiz.');
    }
  };

  return (
    <form onSubmit={handleSubmit} className='form-container'>
      <h2>New Quiz</h2>
        <div className='form-head'>
            <input
                className="quiz-title-input"
                type="text"
                placeholder="Title"
                value={title}
                onChange={(e) => setTitle(e.target.value)}
                required
            />

            <select
                className="quiz-access-select"
                value={access}
                onChange={(e) => setAccess(e.target.value)}
            >
                <option value="Public">Public</option>
                <option value="Protected">Protected</option>
                <option value="Private">Private</option>
            </select>
        </div>
      {pairs.map((pair, idx) => (
        <div className="input-pair" key={idx}>
          <input
            type="text"
            placeholder="ToTranslate"
            value={pair.question}
            onChange={(e) => handlePairChange(idx, 'question', e.target.value)}
          />
          <input
            type="text"
            placeholder="Translation"
            value={pair.answer}
            onChange={(e) => handlePairChange(idx, 'answer', e.target.value)}
          />
        </div>
      ))}

      <button type="submit" className="submit-btn">Create quiz</button>
    </form>
  );
}
