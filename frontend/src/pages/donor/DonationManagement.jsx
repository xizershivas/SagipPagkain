import { useState, useEffect } from 'react';
import DashboardLayout from '../../components/DashboardLayout';
import { donationService, lookupService } from '../../services/api';
import { useAuth } from '../../context/AuthContext';

export default function DonorDonations() {
  const { user } = useAuth();
  const [donations, setDonations] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selected, setSelected] = useState(null);
  const [purposes, setPurposes] = useState([]);
  const [foodBanks, setFoodBanks] = useState([]);
  const [editForm, setEditForm] = useState({});
  const [saving, setSaving] = useState(false);
  const [msg, setMsg] = useState('');

  const load = () => {
    setLoading(true);
    donationService.getAll({ userId: user.userId })
      .then(r => setDonations(r.data)).finally(() => setLoading(false));
  };

  useEffect(() => {
    load();
    lookupService.getPurposes().then(r => setPurposes(r.data));
    lookupService.getFoodBanksByUser(user.userId).then(r => setFoodBanks(r.data));
  }, [user.userId]);

  const openEdit = (d) => {
    setSelected(d);
    setEditForm({
      description: d.strDescription || '',
      foodBankDetailId: d.intFoodBankDetailId,
      purposeId: d.intPurposeId,
      expirationDate: d.dtmExpirationDate?.slice(0, 10),
      isActive: d.ysnActive
    });
  };

  const handleSave = async () => {
    setSaving(true); setMsg('');
    try {
      await donationService.update(selected.intDonationId, editForm);
      setMsg('Donation updated'); load(); setSelected(null);
    } catch { setMsg('Update failed'); }
    finally { setSaving(false); }
  };

  return (
    <DashboardLayout title="My Donations">
      {msg && <div className="alert alert-info py-2 small mb-3">{msg}</div>}
      <div className="data-table-container">
        <div className="data-table-header">
          <h6><i className="bi bi-list-check me-2" style={{ color: 'var(--primary)' }}></i>Donation History</h6>
          <span className="text-muted small">{donations.length} total</span>
        </div>
        <div className="table-responsive">
          <table className="table table-hover mb-0">
            <thead>
              <tr><th>#</th><th>Item</th><th>Qty</th><th>Food Bank</th><th>Purpose</th><th>Date</th><th>Expiry</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
              {loading ? (
                <tr><td colSpan={9} className="text-center py-4"><div className="spinner-border spinner-border-sm"></div></td></tr>
              ) : donations.length === 0 ? (
                <tr><td colSpan={9} className="text-center py-4 text-muted">No donations yet</td></tr>
              ) : donations.map(d => (
                <tr key={d.intDonationId}>
                  <td className="small text-muted">{d.intDonationId}</td>
                  <td className="small fw-semibold">{d.strItem || '—'}</td>
                  <td className="small">{d.intQuantity ? `${d.intQuantity} ${d.strUnit}` : '—'}</td>
                  <td className="small">{d.strFoodBankName}</td>
                  <td className="small">{d.strPurpose}</td>
                  <td className="small">{new Date(d.dtmDate).toLocaleDateString()}</td>
                  <td className="small">{new Date(d.dtmExpirationDate).toLocaleDateString()}</td>
                  <td><span className={`badge-status badge-${d.strStatus?.toLowerCase()}`}>{d.strStatus}</span></td>
                  <td>
                    <button className="btn btn-sm btn-outline-secondary" onClick={() => openEdit(d)}>
                      <i className="bi bi-pencil"></i>
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {selected && (
        <div className="modal show d-block" style={{ background: 'rgba(0,0,0,0.5)' }}>
          <div className="modal-dialog">
            <div className="modal-content">
              <div className="modal-header">
                <h5 className="modal-title">Edit Donation #{selected.intDonationId}</h5>
                <button className="btn-close" onClick={() => setSelected(null)}></button>
              </div>
              <div className="modal-body">
                <div className="mb-3">
                  <label className="form-label small fw-semibold">Description</label>
                  <textarea className="form-control" rows={3} value={editForm.description}
                    onChange={e => setEditForm({ ...editForm, description: e.target.value })}></textarea>
                </div>
                <div className="mb-3">
                  <label className="form-label small fw-semibold">Food Bank</label>
                  <select className="form-select" value={editForm.foodBankDetailId}
                    onChange={e => setEditForm({ ...editForm, foodBankDetailId: +e.target.value })}>
                    {foodBanks.map(fb => <option key={fb.intFoodBankDetailId} value={fb.intFoodBankDetailId}>{fb.strFoodBankName}</option>)}
                  </select>
                </div>
                <div className="row g-2">
                  <div className="col-6">
                    <label className="form-label small fw-semibold">Purpose</label>
                    <select className="form-select" value={editForm.purposeId}
                      onChange={e => setEditForm({ ...editForm, purposeId: +e.target.value })}>
                      {purposes.map(p => <option key={p.intPurposeId} value={p.intPurposeId}>{p.strPurpose}</option>)}
                    </select>
                  </div>
                  <div className="col-6">
                    <label className="form-label small fw-semibold">Expiration Date</label>
                    <input type="date" className="form-control" value={editForm.expirationDate}
                      onChange={e => setEditForm({ ...editForm, expirationDate: e.target.value })} />
                  </div>
                </div>
              </div>
              <div className="modal-footer">
                <button className="btn btn-secondary btn-sm" onClick={() => setSelected(null)}>Cancel</button>
                <button className="btn btn-sm btn-primary-sp" onClick={handleSave} disabled={saving}>
                  {saving ? <span className="spinner-border spinner-border-sm me-1"></span> : null} Save
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </DashboardLayout>
  );
}
