import { useState, useEffect } from 'react';
import DashboardLayout from '../../components/DashboardLayout';
import { donationService } from '../../services/api';
import { useAuth } from '../../context/AuthContext';

export default function DonorTrack() {
  const { user } = useAuth();
  const [donations, setDonations] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selected, setSelected] = useState(null);

  useEffect(() => {
    donationService.getAll({ userId: user.userId })
      .then(r => setDonations(r.data)).finally(() => setLoading(false));
  }, [user.userId]);

  const steps = [
    { label: 'Submitted', icon: 'bi-upload', done: true },
    { label: 'Received by Food Bank', icon: 'bi-building-check', done: (s) => ['Completed', 'Approved'].includes(s) },
    { label: 'In Inventory', icon: 'bi-box-seam', done: (s) => s === 'Completed' },
    { label: 'Distributed', icon: 'bi-house-heart', done: (s) => s === 'Completed' },
  ];

  return (
    <DashboardLayout title="Track Donations">
      <div className="row g-3">
        <div className="col-md-5">
          <div className="form-card p-0 overflow-hidden">
            <div className="p-3 border-bottom">
              <h6 className="mb-0 fw-bold" style={{ color: 'var(--primary)' }}>
                <i className="bi bi-geo-alt-fill me-2"></i>My Donations
              </h6>
            </div>
            {loading ? <div className="text-center py-4"><div className="spinner-border spinner-border-sm"></div></div> : (
              <div>
                {donations.length === 0 ? (
                  <p className="text-center text-muted py-4 small">No donations to track</p>
                ) : donations.map(d => (
                  <div key={d.intDonationId}
                    className="p-3 border-bottom"
                    style={{ cursor: 'pointer', background: selected?.intDonationId === d.intDonationId ? 'rgba(29,104,100,0.05)' : '' }}
                    onClick={() => setSelected(d)}>
                    <div className="d-flex justify-content-between align-items-start">
                      <div>
                        <div className="fw-semibold small">{d.strItem || 'Donation #' + d.intDonationId}</div>
                        <div className="text-muted small">{d.strFoodBankName}</div>
                        <div className="text-muted" style={{ fontSize: '0.75rem' }}>{new Date(d.dtmDate).toLocaleDateString()}</div>
                      </div>
                      <span className={`badge-status badge-${d.strStatus?.toLowerCase()}`}>{d.strStatus}</span>
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>
        <div className="col-md-7">
          {selected ? (
            <div className="form-card">
              <h6 className="fw-bold mb-4" style={{ color: 'var(--primary)' }}>
                Tracking Donation #{selected.intDonationId}
              </h6>
              <div className="mb-4">
                <div className="row g-2 text-center">
                  {steps.map((step, i) => {
                    const done = typeof step.done === 'function' ? step.done(selected.strStatus) : step.done;
                    return (
                      <div key={i} className="col-3">
                        <div className={`rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2`}
                          style={{
                            width: 50, height: 50,
                            background: done ? 'var(--primary)' : '#e9ecef',
                            color: done ? '#fff' : '#aaa'
                          }}>
                          <i className={`bi ${step.icon}`}></i>
                        </div>
                        <div className={`small ${done ? 'fw-semibold' : 'text-muted'}`} style={{ fontSize: '0.75rem' }}>
                          {step.label}
                        </div>
                      </div>
                    );
                  })}
                </div>
              </div>
              <div className="row g-2 small">
                <div className="col-6">
                  <div className="p-2 bg-light rounded">
                    <div className="text-muted">Item</div>
                    <div className="fw-semibold">{selected.strItem || '—'}</div>
                  </div>
                </div>
                <div className="col-6">
                  <div className="p-2 bg-light rounded">
                    <div className="text-muted">Quantity</div>
                    <div className="fw-semibold">{selected.intQuantity ? `${selected.intQuantity} ${selected.strUnit}` : '—'}</div>
                  </div>
                </div>
                <div className="col-6">
                  <div className="p-2 bg-light rounded">
                    <div className="text-muted">Food Bank</div>
                    <div className="fw-semibold">{selected.strFoodBankName}</div>
                  </div>
                </div>
                <div className="col-6">
                  <div className="p-2 bg-light rounded">
                    <div className="text-muted">Purpose</div>
                    <div className="fw-semibold">{selected.strPurpose}</div>
                  </div>
                </div>
                <div className="col-6">
                  <div className="p-2 bg-light rounded">
                    <div className="text-muted">Submitted</div>
                    <div className="fw-semibold">{new Date(selected.dtmDate).toLocaleDateString()}</div>
                  </div>
                </div>
                <div className="col-6">
                  <div className="p-2 bg-light rounded">
                    <div className="text-muted">Expires</div>
                    <div className="fw-semibold">{new Date(selected.dtmExpirationDate).toLocaleDateString()}</div>
                  </div>
                </div>
              </div>
            </div>
          ) : (
            <div className="form-card d-flex align-items-center justify-content-center" style={{ minHeight: 300 }}>
              <div className="text-center text-muted">
                <i className="bi bi-geo-alt fs-1 mb-3 d-block opacity-25"></i>
                <p>Select a donation from the left to track its status</p>
              </div>
            </div>
          )}
        </div>
      </div>
    </DashboardLayout>
  );
}
