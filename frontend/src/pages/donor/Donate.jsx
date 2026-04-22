import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import DashboardLayout from '../../components/DashboardLayout';
import { donationService, lookupService } from '../../services/api';
import { useAuth } from '../../context/AuthContext';

export default function DonorDonate() {
  const { user } = useAuth();
  const navigate = useNavigate();
  const [items, setItems] = useState([]);
  const [purposes, setPurposes] = useState([]);
  const [foodBanks, setFoodBanks] = useState([]);
  const [verificationFiles, setVerificationFiles] = useState([]);
  const [form, setForm] = useState({
    userId: user.userId,
    date: new Date().toISOString().slice(0, 10),
    description: '',
    itemId: '',
    quantity: '',
    categoryId: '',
    unitId: '',
    foodBankDetailId: '',
    purposeId: '',
    expirationDate: '',
  });
  const [loading, setLoading] = useState(false);
  const [msg, setMsg] = useState('');
  const [error, setError] = useState('');

  useEffect(() => {
    Promise.all([
      lookupService.getItems(),
      lookupService.getPurposes(),
      lookupService.getFoodBanksByUser(user.userId)
    ]).then(([itemsRes, purposesRes, fbRes]) => {
      setItems(itemsRes.data);
      setPurposes(purposesRes.data);
      setFoodBanks(fbRes.data);
    });
  }, [user.userId]);

  const handleItemChange = async (itemId) => {
    const item = items.find(i => i.intItemId === +itemId);
    if (!item) return;
    setForm(f => ({ ...f, itemId, categoryId: item.intCategoryId, unitId: item.intUnitId }));

    if (form.expirationDate) {
      try {
        const r = await lookupService.getRecommendedFoodBank(itemId, user.userId);
        if (r.data?.intFoodBankDetailId) {
          setForm(f => ({ ...f, foodBankDetailId: r.data.intFoodBankDetailId }));
        }
      } catch {}
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(''); setMsg(''); setLoading(true);
    try {
      const fd = new FormData();
      Object.entries(form).forEach(([k, v]) => { if (v !== '') fd.append(k, v); });
      verificationFiles.forEach(f => fd.append('verification', f));
      await donationService.create(fd);
      setMsg('Donation submitted successfully!');
      setTimeout(() => navigate('/donor/donations'), 2000);
    } catch (err) {
      setError(err.response?.data?.message || 'Failed to submit donation');
    } finally {
      setLoading(false);
    }
  };

  const selectedItem = items.find(i => i.intItemId === +form.itemId);

  return (
    <DashboardLayout title="Submit Donation">
      <div className="row justify-content-center">
        <div className="col-lg-8">
          {msg && <div className="alert alert-success py-2 mb-3">{msg}</div>}
          {error && <div className="alert alert-danger py-2 mb-3">{error}</div>}
          <div className="form-card">
            <h6 className="fw-bold mb-4" style={{ color: 'var(--primary)' }}>
              <i className="bi bi-gift-fill me-2"></i>Donation Form
            </h6>
            <form onSubmit={handleSubmit}>
              <div className="row g-3">
                <div className="col-md-6">
                  <label className="form-label small fw-semibold">Donation Date *</label>
                  <input type="date" className="form-control" value={form.date}
                    onChange={e => setForm({ ...form, date: e.target.value })} required />
                </div>
                <div className="col-md-6">
                  <label className="form-label small fw-semibold">Expiration Date *</label>
                  <input type="date" className="form-control" value={form.expirationDate}
                    onChange={e => setForm({ ...form, expirationDate: e.target.value })} required />
                </div>
                <div className="col-md-6">
                  <label className="form-label small fw-semibold">Food Item *</label>
                  <select className="form-select" value={form.itemId}
                    onChange={e => handleItemChange(e.target.value)} required>
                    <option value="">Select Item</option>
                    {items.map(i => <option key={i.intItemId} value={i.intItemId}>{i.strItem}</option>)}
                  </select>
                </div>
                <div className="col-md-3">
                  <label className="form-label small fw-semibold">Category</label>
                  <input className="form-control" value={selectedItem?.strCategory || ''} readOnly style={{ background: '#f8f9fa' }} />
                </div>
                <div className="col-md-3">
                  <label className="form-label small fw-semibold">Unit</label>
                  <input className="form-control" value={selectedItem?.strUnit || ''} readOnly style={{ background: '#f8f9fa' }} />
                </div>
                <div className="col-md-4">
                  <label className="form-label small fw-semibold">Quantity *</label>
                  <input type="number" min={1} className="form-control" value={form.quantity}
                    onChange={e => setForm({ ...form, quantity: e.target.value })} required />
                </div>
                <div className="col-md-8">
                  <label className="form-label small fw-semibold">Food Bank *</label>
                  <select className="form-select" value={form.foodBankDetailId}
                    onChange={e => setForm({ ...form, foodBankDetailId: e.target.value })} required>
                    <option value="">Select Food Bank</option>
                    {foodBanks.map(fb => <option key={fb.intFoodBankDetailId} value={fb.intFoodBankDetailId}>{fb.strFoodBankName}</option>)}
                  </select>
                </div>
                <div className="col-md-6">
                  <label className="form-label small fw-semibold">Purpose *</label>
                  <select className="form-select" value={form.purposeId}
                    onChange={e => setForm({ ...form, purposeId: e.target.value })} required>
                    <option value="">Select Purpose</option>
                    {purposes.map(p => <option key={p.intPurposeId} value={p.intPurposeId}>{p.strPurpose}</option>)}
                  </select>
                </div>
                <div className="col-md-6">
                  <label className="form-label small fw-semibold">Verification Documents (PDF, max 5MB each)</label>
                  <input type="file" className="form-control" accept=".pdf" multiple
                    onChange={e => setVerificationFiles(Array.from(e.target.files))} />
                </div>
                <div className="col-12">
                  <label className="form-label small fw-semibold">Description</label>
                  <textarea className="form-control" rows={3} value={form.description}
                    onChange={e => setForm({ ...form, description: e.target.value })}
                    placeholder="Optional description of the donated items..."></textarea>
                </div>
              </div>
              <div className="d-flex gap-2 mt-4">
                <button type="submit" className="btn btn-primary-sp px-4" disabled={loading}>
                  {loading ? <span className="spinner-border spinner-border-sm me-2"></span> : <i className="bi bi-send-fill me-2"></i>}
                  Submit Donation
                </button>
                <button type="button" className="btn btn-outline-secondary px-4" onClick={() => navigate('/donor/dashboard')}>
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}
