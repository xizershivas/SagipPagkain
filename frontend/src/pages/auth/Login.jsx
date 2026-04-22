import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { authService } from '../../services/api';
import { useAuth } from '../../context/AuthContext';

export default function Login() {
  const [form, setForm] = useState({ username: '', password: '' });
  const [showPw, setShowPw] = useState(false);
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const { login } = useAuth();
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      const res = await authService.login(form);
      const { userId, username, fullName, token, role } = res.data;
      login({ userId, username, fullName, token, role });
      const routes = { Admin: '/admin/dashboard', Donor: '/donor/dashboard', FoodBank: '/ngo/dashboard', Beneficiary: '/beneficiary/request' };
      navigate(routes[role] || '/');
    } catch (err) {
      setError(err.response?.data?.message || 'Login failed');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="auth-page">
      <div className="auth-card">
        <div className="text-center mb-4">
          <img src="/img/sagip-pagkain-logo.jpeg" alt="Sagip Pagkain" style={{ width: 80, height: 80, objectFit: 'contain', borderRadius: 12 }} />
          <h4 className="mt-2 mb-0 fw-bold" style={{ color: 'var(--primary)' }}>Sagip Pagkain</h4>
          <p className="text-muted small">Sign in to your account</p>
        </div>

        {error && <div className="alert alert-danger py-2 small">{error}</div>}

        <form onSubmit={handleSubmit}>
          <div className="mb-3">
            <label className="form-label fw-semibold">Username</label>
            <div className="input-group">
              <span className="input-group-text"><i className="bi bi-person"></i></span>
              <input type="text" className="form-control" value={form.username}
                onChange={e => setForm({ ...form, username: e.target.value })} required />
            </div>
          </div>
          <div className="mb-4">
            <label className="form-label fw-semibold">Password</label>
            <div className="input-group">
              <span className="input-group-text"><i className="bi bi-lock"></i></span>
              <input type={showPw ? 'text' : 'password'} className="form-control"
                value={form.password} onChange={e => setForm({ ...form, password: e.target.value })} required />
              <button type="button" className="btn btn-outline-secondary" onClick={() => setShowPw(!showPw)}>
                <i className={`bi ${showPw ? 'bi-eye-slash' : 'bi-eye'}`}></i>
              </button>
            </div>
          </div>
          <button type="submit" className="btn btn-primary-sp w-100 py-2" disabled={loading}>
            {loading ? <span className="spinner-border spinner-border-sm me-2" /> : null}
            Sign In
          </button>
        </form>

        <p className="text-center mt-3 small text-muted">
          Don't have an account? <Link to="/signup" style={{ color: 'var(--primary)' }}>Sign up</Link>
        </p>
        <p className="text-center">
          <Link to="/" className="small text-muted"><i className="bi bi-arrow-left me-1"></i>Back to Home</Link>
        </p>
      </div>
    </div>
  );
}
