import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import DashboardLayout from '../../components/DashboardLayout';
import { donationService } from '../../services/api';
import { useAuth } from '../../context/AuthContext';

export default function DonorDashboard() {
  const { user } = useAuth();
  const navigate = useNavigate();
  const [donations, setDonations] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    donationService.getAll({ userId: user.userId })
      .then(r => setDonations(r.data))
      .finally(() => setLoading(false));
  }, [user.userId]);

  const pending = donations.filter(d => d.strStatus === 'Pending').length;
  const completed = donations.filter(d => d.strStatus === 'Completed').length;

  return (
    <DashboardLayout title="Donor Dashboard">
      <div className="row g-3 mb-4">
        {[
          { label: 'Total Donations', value: donations.length, icon: 'bi-heart-fill', cls: 'teal', action: () => navigate('/donor/donations') },
          { label: 'Pending', value: pending, icon: 'bi-clock', cls: 'gold', action: null },
          { label: 'Completed', value: completed, icon: 'bi-check-circle-fill', cls: 'green', action: null },
        ].map((s, i) => (
          <div key={i} className="col-md-4">
            <div className="stat-card" style={{ cursor: s.action ? 'pointer' : 'default' }} onClick={s.action || undefined}>
              <div className={`icon ${s.cls}`}><i className={`bi ${s.icon}`}></i></div>
              <div className="stat-info"><h3>{s.value}</h3><p>{s.label}</p></div>
            </div>
          </div>
        ))}
      </div>

      <div className="row g-3">
        <div className="col-md-8">
          <div className="form-card">
            <h6 className="fw-bold mb-3" style={{ color: 'var(--primary)' }}>Recent Donations</h6>
            {loading ? <div className="text-center py-3"><div className="spinner-border spinner-border-sm"></div></div> : (
              <div className="table-responsive">
                <table className="table table-hover table-sm mb-0">
                  <thead>
                    <tr><th>Item</th><th>Food Bank</th><th>Date</th><th>Status</th></tr>
                  </thead>
                  <tbody>
                    {donations.slice(0, 5).map(d => (
                      <tr key={d.intDonationId}>
                        <td className="small">{d.strItem || '—'}</td>
                        <td className="small">{d.strFoodBankName}</td>
                        <td className="small">{new Date(d.dtmDate).toLocaleDateString()}</td>
                        <td><span className={`badge-status badge-${d.strStatus.toLowerCase()}`}>{d.strStatus}</span></td>
                      </tr>
                    ))}
                    {donations.length === 0 && <tr><td colSpan={4} className="text-center text-muted small py-3">No donations yet</td></tr>}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        </div>
        <div className="col-md-4">
          <div className="form-card text-center" style={{ background: 'linear-gradient(135deg, var(--primary), var(--primary-dark))', color: '#fff' }}>
            <i className="bi bi-gift fs-1 mb-3 d-block" style={{ color: 'var(--accent)' }}></i>
            <h5 className="fw-bold mb-2">Make a Donation</h5>
            <p className="small opacity-75 mb-3">Help feed families in need by donating food today.</p>
            <button className="btn fw-semibold px-4" style={{ background: 'var(--accent)', color: '#000' }}
              onClick={() => navigate('/donor/donate')}>
              <i className="bi bi-plus-lg me-2"></i>Donate Now
            </button>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}
