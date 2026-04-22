import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import DashboardLayout from '../../components/DashboardLayout';
import { beneficiaryService, lookupService } from '../../services/api';
import { useAuth } from '../../context/AuthContext';

export default function AssistanceRequest() {
  const { user } = useAuth();
  const navigate = useNavigate();
  const [items, setItems] = useState([]);
  const [purposes, setPurposes] = useState([]);
  const [foodBanks, setFoodBanks] = useState([]);
  const [beneficiaryId, setBeneficiaryId] = useState(null);
  const [selectedItems, setSelectedItems] = useState([]);
  const [docFile, setDocFile] = useState(null);
  const [form, setForm] = useState({
    requestType: 'Regular',
    urgencyLevel: 'Low',
    pickupDate: '',
    purposeId: '',
    foodbankId: '',
  });
  const [loading, setLoading] = useState(false);
  const [msg, setMsg] = useState('');
  const [error, setError] = useState('');

  useEffect(() => {
    Promise.all([
      beneficiaryService.getProfile(user.userId),
      lookupService.getItems(),
      lookupService.getPurposes(),
      lookupService.getFoodBanks()
    ]).then(([profileRes, itemsRes, purposesRes, fbRes]) => {
      setBeneficiaryId(profileRes.data.intBeneficiaryId);
      setItems(itemsRes.data);
      setPurposes(purposesRes.data);
      setFoodBanks(fbRes.data);
    });
  }, [user.userId]);

  const toggleItem = (id) => {
    setSelectedItems(prev =>
      prev.includes(id) ? prev.filter(i => i !== id) : [...prev, id]
    );
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (selectedItems.length === 0) { setError('Please select at least one item'); return; }
    setError(''); setMsg(''); setLoading(true);
    try {
      const fd = new FormData();
      fd.append('beneficiaryId', beneficiaryId);
      fd.append('requestType', form.requestType);
      fd.append('urgencyLevel', form.urgencyLevel);
      fd.append('pickupDate', form.pickupDate);
      fd.append('purposeId', form.purposeId);
      fd.append('foodbankId', form.foodbankId);
      selectedItems.forEach(id => fd.append('itemsNeeded', id));
      if (docFile) fd.append('document', docFile);
      await beneficiaryService.submitRequest(fd);
      setMsg('Request submitted successfully!');
      setTimeout(() => navigate('/beneficiary/status'), 2000);
    } catch (err) {
      setError(err.response?.data?.message || 'Failed to submit request');
    } finally {
      setLoading(false);
    }
  };

  return (
    <DashboardLayout title="Request Assistance">
      <div className="row justify-content-center">
        <div className="col-lg-9">
          {msg && <div className="alert alert-success py-2 mb-3">{msg}</div>}
          {error && <div className="alert alert-danger py-2 mb-3">{error}</div>}
          <div className="form-card">
            <h6 className="fw-bold mb-4" style={{ color: 'var(--primary)' }}>
              <i className="bi bi-hand-index-fill me-2"></i>Assistance Request Form
            </h6>
            <form onSubmit={handleSubmit}>
              <div className="row g-3">
                <div className="col-md-4">
                  <label className="form-label small fw-semibold">Request Type *</label>
                  <select className="form-select" value={form.requestType}
                    onChange={e => setForm({ ...form, requestType: e.target.value })} required>
                    <option>Regular</option>
                    <option>Emergency</option>
                    <option>Special Needs</option>
                  </select>
                </div>
                <div className="col-md-4">
                  <label className="form-label small fw-semibold">Urgency Level *</label>
                  <select className="form-select" value={form.urgencyLevel}
                    onChange={e => setForm({ ...form, urgencyLevel: e.target.value })} required>
                    <option>Low</option>
                    <option>Medium</option>
                    <option>High</option>
                  </select>
                </div>
                <div className="col-md-4">
                  <label className="form-label small fw-semibold">Preferred Pickup Date *</label>
                  <input type="date" className="form-control" value={form.pickupDate}
                    onChange={e => setForm({ ...form, pickupDate: e.target.value })} required />
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
                  <label className="form-label small fw-semibold">Preferred Food Bank *</label>
                  <select className="form-select" value={form.foodbankId}
                    onChange={e => setForm({ ...form, foodbankId: e.target.value })} required>
                    <option value="">Select Food Bank</option>
                    {foodBanks.map(fb => <option key={fb.intFoodBankDetailId} value={fb.intFoodBankDetailId}>{fb.strFoodBankName}</option>)}
                  </select>
                </div>
                <div className="col-12">
                  <label className="form-label small fw-semibold">Food Items Needed * <span className="text-muted">(select all that apply)</span></label>
                  <div className="row g-2 mt-1">
                    {items.map(item => (
                      <div key={item.intItemId} className="col-6 col-md-3">
                        <div
                          className="p-2 rounded border small text-center"
                          style={{
                            cursor: 'pointer',
                            background: selectedItems.includes(item.intItemId) ? 'rgba(29,104,100,0.1)' : '#fff',
                            borderColor: selectedItems.includes(item.intItemId) ? 'var(--primary)' : '#dee2e6',
                            color: selectedItems.includes(item.intItemId) ? 'var(--primary)' : '#333',
                            transition: '0.2s'
                          }}
                          onClick={() => toggleItem(item.intItemId)}>
                          {selectedItems.includes(item.intItemId) && <i className="bi bi-check-circle-fill me-1"></i>}
                          {item.strItem}
                          <div className="text-muted" style={{ fontSize: '0.7rem' }}>{item.strCategory}</div>
                        </div>
                      </div>
                    ))}
                  </div>
                  {selectedItems.length > 0 && (
                    <div className="mt-2 small text-success">
                      <i className="bi bi-check-circle me-1"></i>
                      {selectedItems.length} item(s) selected
                    </div>
                  )}
                </div>
                <div className="col-12">
                  <label className="form-label small fw-semibold">Supporting Document (PDF, max 5MB)</label>
                  <input type="file" className="form-control" accept=".pdf"
                    onChange={e => setDocFile(e.target.files[0])} />
                  <div className="form-text">Certificate of Indigency or similar document</div>
                </div>
              </div>
              <div className="d-flex gap-2 mt-4">
                <button type="submit" className="btn btn-primary-sp px-4" disabled={loading}>
                  {loading ? <span className="spinner-border spinner-border-sm me-2"></span> : <i className="bi bi-send-fill me-2"></i>}
                  Submit Request
                </button>
                <button type="button" className="btn btn-outline-secondary px-4" onClick={() => navigate('/beneficiary/status')}>
                  View My Requests
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}
