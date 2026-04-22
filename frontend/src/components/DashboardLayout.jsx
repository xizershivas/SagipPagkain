import { NavLink, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const navItems = {
  Admin: [
    { to: '/admin/dashboard', icon: 'bi-speedometer2', label: 'Dashboard' },
    { to: '/admin/donations', icon: 'bi-heart-fill', label: 'Donations' },
    { to: '/admin/inventory', icon: 'bi-box-seam', label: 'Inventory' },
    { to: '/admin/beneficiaries', icon: 'bi-people-fill', label: 'Beneficiaries' },
    { to: '/admin/volunteers', icon: 'bi-person-badge', label: 'Volunteers' },
  ],
  Donor: [
    { to: '/donor/dashboard', icon: 'bi-speedometer2', label: 'Dashboard' },
    { to: '/donor/donate', icon: 'bi-gift-fill', label: 'Donate' },
    { to: '/donor/donations', icon: 'bi-list-check', label: 'My Donations' },
    { to: '/donor/inventory', icon: 'bi-box-seam', label: 'Inventory' },
    { to: '/donor/track', icon: 'bi-geo-alt-fill', label: 'Track Donation' },
  ],
  FoodBank: [
    { to: '/ngo/dashboard', icon: 'bi-speedometer2', label: 'Dashboard' },
    { to: '/ngo/donations', icon: 'bi-heart-fill', label: 'Donations' },
    { to: '/ngo/inventory', icon: 'bi-box-seam', label: 'Inventory' },
    { to: '/ngo/requests', icon: 'bi-clipboard-check', label: 'Request Approval' },
  ],
  Beneficiary: [
    { to: '/beneficiary/request', icon: 'bi-hand-index-fill', label: 'Request Assistance' },
    { to: '/beneficiary/status', icon: 'bi-clock-history', label: 'My Requests' },
    { to: '/beneficiary/food', icon: 'bi-basket2-fill', label: 'Available Food' },
    { to: '/beneficiary/foodbank', icon: 'bi-building', label: 'Food Banks' },
  ],
};

export default function DashboardLayout({ children, title }) {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = () => {
    logout();
    navigate('/');
  };

  const items = navItems[user?.role] || [];

  return (
    <div className="dashboard-wrapper">
      <aside className="sidebar">
        <div className="sidebar-brand">
          <img src="/img/sagiplogo.png" alt="Logo" />
          <span>Sagip Pagkain</span>
        </div>
        <nav className="sidebar-nav">
          {items.map(item => (
            <NavLink key={item.to} to={item.to} className={({ isActive }) => isActive ? 'active' : ''}>
              <i className={`bi ${item.icon}`}></i>
              {item.label}
            </NavLink>
          ))}
        </nav>
        <div className="sidebar-footer">
          <div className="text-white-50 small mb-2 px-1">
            <i className="bi bi-person-circle me-1"></i>
            {user?.fullName || user?.username}
          </div>
          <button onClick={handleLogout}>
            <i className="bi bi-box-arrow-right me-1"></i> Logout
          </button>
        </div>
      </aside>

      <main className="main-content">
        <div className="top-header">
          <h5><i className="bi bi-grid-fill me-2" style={{ color: 'var(--primary)' }}></i>{title}</h5>
          <div className="text-muted small">
            <i className="bi bi-person me-1"></i>
            {user?.role} &bull; {user?.username}
          </div>
        </div>
        {children}
      </main>
    </div>
  );
}
