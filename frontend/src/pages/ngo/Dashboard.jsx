import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import DashboardLayout from '../../components/DashboardLayout';
import { donationService, beneficiaryService, inventoryService } from '../../services/api';
import { useAuth } from '../../context/AuthContext';

export default function NgoDashboard() {
  const { user } = useAuth();
  const navigate = useNavigate();
  const [donations, setDonations] = useState([]);
  const [requests, setRequests] = useState([]);
  const [inventory, setInventory] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    Promise.all([
      donationService.getAll({ foodBankUserId: user.userId }),
      beneficiaryService.getRequests({ foodBankUserId: user.userId }),
      inventoryService.getAll({ userId: user.userId, limit: 5 })
    ]).then(([dRes, rRes, iRes]) => {
      setDonations(dRes.data);
      setRequests(rRes.data);
      setInventory(iRes.data.data || []);
    }).finally(() => setLoading(false));
  }, [user.userId]);

  const pendingReq = requests.filter(r => r.strStatus === 'Pending').length;

  return (
    <DashboardLayout title="Food Bank Dashboard">
      {loading ? <div className="text-center py-5"><div className="spinner-border" style={{ color: 'var(--primary)' }}></div></div> : (
        <>
          <div className="row g-3 mb-4">
            {[
              { label: 'Total Donations', value: donations.length, icon: 'bi-heart-fill', cls: 'teal', action: () => navigate('/ngo/donations') },
              { label: 'Pending Requests', value: pendingReq, icon: 'bi-clipboard-check', cls: 'gold', action: () => navigate('/ngo/requests') },
              { label: 'Inventory Items', value: inventory.length, icon: 'bi-box-seam', cls: 'green', action: () => navigate('/ngo/inventory') },
              { label: 'Total Requests', value: requests.length, icon: 'bi-people-fill', cls: 'red', action: () => navigate('/ngo/requests') },
            ].map((s, i) => (
              <div key={i} className="col-6 col-md-3">
                <div className="stat-card" style={{ cursor: 'pointer' }} onClick={s.action}>
                  <div className={`icon ${s.cls}`}><i className={`bi ${s.icon}`}></i></div>
                  <div className="stat-info"><h3>{s.value}</h3><p>{s.label}</p></div>
                </div>
              </div>
            ))}
          </div>

          <div className="row g-3">
            <div className="col-md-6">
              <div className="form-card">
                <h6 className="fw-bold mb-3" style={{ color: 'var(--primary)' }}>Recent Donations</h6>
                <div className="table-responsive">
                  <table className="table table-hover table-sm mb-0">
                    <thead><tr><th>Donor</th><th>Item</th><th>Qty</th><th>Status</th></tr></thead>
                    <tbody>
                      {donations.slice(0, 5).map(d => (
                        <tr key={d.intDonationId}>
                          <td className="small">{d.strDonorName}</td>
                          <td className="small">{d.strItem || '—'}</td>
                          <td className="small">{d.intQuantity || '—'}</td>
                          <td><span className={`badge-status badge-${d.strStatus?.toLowerCase()}`}>{d.strStatus}</span></td>
                        </tr>
                      ))}
                      {donations.length === 0 && <tr><td colSpan={4} className="text-center text-muted small py-2">No donations</td></tr>}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div className="col-md-6">
              <div className="form-card">
                <h6 className="fw-bold mb-3" style={{ color: 'var(--primary)' }}>Pending Assistance Requests</h6>
                <div className="list-group list-group-flush">
                  {requests.filter(r => r.strStatus === 'Pending').slice(0, 5).map(r => (
                    <div key={r.intBeneficiaryRequestId} className="list-group-item px-0 py-2">
                      <div className="d-flex justify-content-between">
                        <span className="small fw-semibold">{r.strBeneficiaryName}</span>
                        <span className={`badge-status badge-${r.strUrgencyLevel?.toLowerCase() === 'high' ? 'rejected' : 'pending'}`}>
                          {r.strUrgencyLevel}
                        </span>
                      </div>
                      <div className="text-muted" style={{ fontSize: '0.75rem' }}>{r.strRequestNo} • {r.strRequestType}</div>
                    </div>
                  ))}
                  {requests.filter(r => r.strStatus === 'Pending').length === 0 && (
                    <p className="text-muted small text-center py-2">No pending requests</p>
                  )}
                </div>
              </div>
            </div>
          </div>
        </>
      )}
    </DashboardLayout>
  );
}
