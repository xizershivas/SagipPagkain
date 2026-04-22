import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import DashboardLayout from '../../components/DashboardLayout';
import { donationService, beneficiaryService, volunteerService, notificationService } from '../../services/api';
import { useAuth } from '../../context/AuthContext';
import { Bar, Pie } from 'react-chartjs-2';
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, ArcElement, Tooltip, Legend } from 'chart.js';
ChartJS.register(CategoryScale, LinearScale, BarElement, ArcElement, Tooltip, Legend);

export default function AdminDashboard() {
  const { user } = useAuth();
  const navigate = useNavigate();
  const [stats, setStats] = useState(null);
  const [beneficiaryCount, setBeneficiaryCount] = useState(0);
  const [volunteerCount, setVolunteerCount] = useState(0);
  const [notifications, setNotifications] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    Promise.all([
      donationService.getStats(user.userId),
      beneficiaryService.getAll(),
      volunteerService.getAll(),
      notificationService.getUnseen()
    ]).then(([statsRes, bRes, vRes, nRes]) => {
      setStats(statsRes.data);
      setBeneficiaryCount(bRes.data.length);
      setVolunteerCount(vRes.data.length);
      setNotifications(nRes.data);
    }).catch(console.error).finally(() => setLoading(false));
  }, [user.userId]);

  const monthLabels = stats?.monthlyData?.map(m => `${m.month}/${m.year}`) || [];
  const monthCounts = stats?.monthlyData?.map(m => m.count) || [];
  const purposeLabels = stats?.purposeData?.map(p => p.purpose) || [];
  const purposeCounts = stats?.purposeData?.map(p => p.count) || [];

  return (
    <DashboardLayout title="Admin Dashboard">
      {loading ? (
        <div className="text-center py-5"><div className="spinner-border" style={{ color: 'var(--primary)' }}></div></div>
      ) : (
        <>
          {/* Stat Cards */}
          <div className="row g-3 mb-4">
            {[
              { label: 'Total Donations', value: stats?.totalDonations || 0, icon: 'bi-heart-fill', cls: 'teal', action: () => navigate('/admin/donations') },
              { label: 'Beneficiaries', value: beneficiaryCount, icon: 'bi-people-fill', cls: 'gold', action: () => navigate('/admin/beneficiaries') },
              { label: 'Volunteers', value: volunteerCount, icon: 'bi-person-badge', cls: 'green', action: () => navigate('/admin/volunteers') },
              { label: 'Notifications', value: notifications.length, icon: 'bi-bell-fill', cls: 'red', action: null },
            ].map((s, i) => (
              <div key={i} className="col-6 col-md-3">
                <div className="stat-card" style={{ cursor: s.action ? 'pointer' : 'default' }} onClick={s.action || undefined}>
                  <div className={`icon ${s.cls}`}><i className={`bi ${s.icon}`}></i></div>
                  <div className="stat-info">
                    <h3>{s.value}</h3>
                    <p>{s.label}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>

          {/* Charts */}
          <div className="row g-3">
            <div className="col-lg-8">
              <div className="form-card">
                <h6 className="fw-bold mb-3" style={{ color: 'var(--primary)' }}>Monthly Donations</h6>
                {monthLabels.length > 0 ? (
                  <Bar data={{
                    labels: monthLabels,
                    datasets: [{ label: 'Donations', data: monthCounts, backgroundColor: 'rgba(29,104,100,0.7)', borderRadius: 6 }]
                  }} options={{ responsive: true, plugins: { legend: { display: false } } }} />
                ) : <p className="text-muted small text-center">No data available</p>}
              </div>
            </div>
            <div className="col-lg-4">
              <div className="form-card">
                <h6 className="fw-bold mb-3" style={{ color: 'var(--primary)' }}>Donation by Purpose</h6>
                {purposeLabels.length > 0 ? (
                  <Pie data={{
                    labels: purposeLabels,
                    datasets: [{ data: purposeCounts, backgroundColor: ['#1d6864','#F7B32B','#28a745','#dc3545','#6c757d'] }]
                  }} options={{ responsive: true }} />
                ) : <p className="text-muted small text-center">No data available</p>}
              </div>
            </div>
          </div>

          {/* Notifications */}
          {notifications.length > 0 && (
            <div className="form-card mt-3">
              <h6 className="fw-bold mb-3" style={{ color: 'var(--primary)' }}>
                <i className="bi bi-bell me-2"></i>Recent Notifications
              </h6>
              <div className="list-group list-group-flush">
                {notifications.slice(0, 5).map(n => (
                  <div key={n.intNotificationId} className="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                    <span className="small">
                      <i className="bi bi-dot text-warning fs-5"></i>
                      New record in <strong>{n.strSourceTable}</strong> (ID: {n.intSourceId})
                    </span>
                    <span className="text-muted small">{new Date(n.dtmCreatedAt).toLocaleDateString()}</span>
                  </div>
                ))}
              </div>
            </div>
          )}
        </>
      )}
    </DashboardLayout>
  );
}
