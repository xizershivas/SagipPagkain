import { useState, useEffect } from 'react';
import DashboardLayout from '../../components/DashboardLayout';
import { donationService } from '../../services/api';
import { useAuth } from '../../context/AuthContext';

export default function NgoDonations() {
  const { user } = useAuth();
  const [donations, setDonations] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');

  useEffect(() => {
    donationService.getAll({ foodBankUserId: user.userId })
      .then(r => setDonations(r.data)).finally(() => setLoading(false));
  }, [user.userId]);

  const filtered = donations.filter(d =>
    d.strDonorName?.toLowerCase().includes(search.toLowerCase()) ||
    d.strItem?.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <DashboardLayout title="Donations Received">
      <div className="data-table-container">
        <div className="data-table-header">
          <h6><i className="bi bi-heart-fill me-2" style={{ color: 'var(--primary)' }}></i>All Donations</h6>
          <input type="text" className="form-control form-control-sm" style={{ width: 250 }}
            placeholder="Search donor or item..." value={search} onChange={e => setSearch(e.target.value)} />
        </div>
        <div className="table-responsive">
          <table className="table table-hover mb-0">
            <thead>
              <tr><th>#</th><th>Donor</th><th>Item</th><th>Qty</th><th>Purpose</th><th>Date</th><th>Expiry</th><th>Status</th></tr>
            </thead>
            <tbody>
              {loading ? (
                <tr><td colSpan={8} className="text-center py-4"><div className="spinner-border spinner-border-sm"></div></td></tr>
              ) : filtered.length === 0 ? (
                <tr><td colSpan={8} className="text-center py-4 text-muted">No donations found</td></tr>
              ) : filtered.map(d => (
                <tr key={d.intDonationId}>
                  <td className="small text-muted">{d.intDonationId}</td>
                  <td className="small fw-semibold">{d.strDonorName}</td>
                  <td className="small">{d.strItem || '—'}</td>
                  <td className="small">{d.intQuantity ? `${d.intQuantity} ${d.strUnit}` : '—'}</td>
                  <td className="small">{d.strPurpose}</td>
                  <td className="small">{new Date(d.dtmDate).toLocaleDateString()}</td>
                  <td className="small">{new Date(d.dtmExpirationDate).toLocaleDateString()}</td>
                  <td><span className={`badge-status badge-${d.strStatus?.toLowerCase()}`}>{d.strStatus}</span></td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </DashboardLayout>
  );
}
