import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { authService } from '../../services/api';

export default function Signup() {
  const [form, setForm] = useState({
    username: '', fullName: '', contact: '', email: '',
    password: '', confirmPassword: '', accountType: 'donor',
    address: '', latitude: '', longitude: '', salary: ''
  });
  const [showPw, setShowPw] = useState(false);
  const [docFile, setDocFile] = useState(null);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(''); setSuccess('');
    setLoading(true);
    try {
      const fd = new FormData();
      Object.entries(form).forEach(([k, v]) => { if (v) fd.append(k, v); });
      if (docFile) fd.append('uploadDocu', docFile);
      await authService.register(fd);
      setSuccess('Registration successful! You can now sign in.');
      setTimeout(() => navigate('/login'), 2000);
    } catch (err) {
      setError(err.response?.data?.message || 'Registration failed');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="auth-page py-4">
      <div className="auth-card" style={{ maxWidth: 520 }}>
        <div className="text-center mb-4">
          <img src="/img/sagip-pagkain-logo.jpeg" alt="Logo" style={{ width: 70, borderRadius: 10 }} />
          <h4 className="mt-2 mb-0 fw-bold" style={{ color: 'var(--primary)' }}>Create Account</h4>
          <p className="text-muted small">Join Sagip Pagkain today</p>
        </div>

        {error && <div className="alert alert-danger py-2 small">{error}</div>}
        {success && <div className="alert alert-success py-2 small">{success}</div>}

        <form onSubmit={handleSubmit}>
          <div className="mb-3">
            <label className="form-label fw-semibold small">Account Type</label>
            <select className="form-select" value={form.accountType}
              onChange={e => setForm({ ...form, accountType: e.target.value })}>
              <option value="donor">Donor</option>
              <option value="foodbank">Food Bank</option>
              <option value="beneficiary">Beneficiary</option>
            </select>
          </div>
          <div className="row g-2 mb-3">
            <div className="col-6">
              <label className="form-label fw-semibold small">Username</label>
              <input type="text" className="form-control" value={form.username}
                onChange={e => setForm({ ...form, username: e.target.value })} required />
            </div>
            <div className="col-6">
              <label className="form-label fw-semibold small">Full Name</label>
              <input type="text" className="form-control" value={form.fullName}
                onChange={e => setForm({ ...form, fullName: e.target.value })} required />
            </div>
          </div>
          <div className="row g-2 mb-3">
            <div className="col-6">
              <label className="form-label fw-semibold small">Contact</label>
              <input type="text" className="form-control" value={form.contact}
                onChange={e => setForm({ ...form, contact: e.target.value })} />
            </div>
            <div className="col-6">
              <label className="form-label fw-semibold small">Email</label>
              <input type="email" className="form-control" value={form.email}
                onChange={e => setForm({ ...form, email: e.target.value })} />
            </div>
          </div>
          {(form.accountType === 'donor' || form.accountType === 'beneficiary') && (
            <div className="mb-3">
              <label className="form-label fw-semibold small">Address</label>
              <input type="text" className="form-control" value={form.address}
                onChange={e => setForm({ ...form, address: e.target.value })}
                placeholder="Include municipality (e.g. Quezon City)" />
            </div>
          )}
          {form.accountType === 'beneficiary' && (
            <>
              <div className="row g-2 mb-3">
                <div className="col-4">
                  <label className="form-label fw-semibold small">Latitude</label>
                  <input type="number" step="any" className="form-control" value={form.latitude}
                    onChange={e => setForm({ ...form, latitude: e.target.value })} />
                </div>
                <div className="col-4">
                  <label className="form-label fw-semibold small">Longitude</label>
                  <input type="number" step="any" className="form-control" value={form.longitude}
                    onChange={e => setForm({ ...form, longitude: e.target.value })} />
                </div>
                <div className="col-4">
                  <label className="form-label fw-semibold small">Monthly Salary</label>
                  <input type="number" className="form-control" value={form.salary}
                    onChange={e => setForm({ ...form, salary: e.target.value })} />
                </div>
              </div>
              <div className="mb-3">
                <label className="form-label fw-semibold small">Certificate of Indigency (PDF)</label>
                <input type="file" className="form-control" accept=".pdf"
                  onChange={e => setDocFile(e.target.files[0])} />
              </div>
            </>
          )}
          <div className="row g-2 mb-4">
            <div className="col-6">
              <label className="form-label fw-semibold small">Password</label>
              <div className="input-group">
                <input type={showPw ? 'text' : 'password'} className="form-control"
                  value={form.password} onChange={e => setForm({ ...form, password: e.target.value })} required />
                <button type="button" className="btn btn-outline-secondary" onClick={() => setShowPw(!showPw)}>
                  <i className={`bi ${showPw ? 'bi-eye-slash' : 'bi-eye'}`}></i>
                </button>
              </div>
              <div className="form-text">Min 8 chars, 1 uppercase, 1 number, 1 special</div>
            </div>
            <div className="col-6">
              <label className="form-label fw-semibold small">Confirm Password</label>
              <input type={showPw ? 'text' : 'password'} className="form-control"
                value={form.confirmPassword}
                onChange={e => setForm({ ...form, confirmPassword: e.target.value })} required />
            </div>
          </div>
          <button type="submit" className="btn btn-primary-sp w-100 py-2" disabled={loading}>
            {loading ? <span className="spinner-border spinner-border-sm me-2" /> : null}
            Create Account
          </button>
        </form>

        <p className="text-center mt-3 small text-muted">
          Already have an account? <Link to="/login" style={{ color: 'var(--primary)' }}>Sign in</Link>
        </p>
      </div>
    </div>
  );
}
