import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider, useAuth } from './context/AuthContext';
import Landing from './pages/landing/Landing';
import Login from './pages/auth/Login';
import Signup from './pages/auth/Signup';
import AdminDashboard from './pages/admin/Dashboard';
import AdminDonations from './pages/admin/DonationManagement';
import AdminInventory from './pages/admin/InventoryManagement';
import AdminBeneficiaries from './pages/admin/ManageBeneficiary';
import AdminVolunteers from './pages/admin/VolunteerManagement';
import DonorDashboard from './pages/donor/Dashboard';
import DonorDonate from './pages/donor/Donate';
import DonorDonations from './pages/donor/DonationManagement';
import DonorInventory from './pages/donor/InventoryManagement';
import DonorTrack from './pages/donor/TrackDonation';
import NgoDashboard from './pages/ngo/Dashboard';
import NgoDonations from './pages/ngo/DonationManagement';
import NgoInventory from './pages/ngo/InventoryManagement';
import NgoRequests from './pages/ngo/RequestApproval';
import BeneficiaryRequest from './pages/beneficiary/AssistanceRequest';
import BeneficiaryStatus from './pages/beneficiary/RequestStatus';
import BeneficiaryFood from './pages/beneficiary/AvailableFoodItem';
import BeneficiaryFoodBank from './pages/beneficiary/FoodBankCenter';
import FloatingChatbot from './components/FloatingChatbot';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import './index.css';

function PrivateRoute({ children, roles }) {
  const { user } = useAuth();
  if (!user) return <Navigate to="/login" replace />;
  if (roles && !roles.includes(user.role)) return <Navigate to="/" replace />;
  return children;
}

function AppRoutes() {
  return (
    <Routes>
      <Route path="/" element={<Landing />} />
      <Route path="/login" element={<Login />} />
      <Route path="/signup" element={<Signup />} />

      <Route path="/admin/dashboard" element={<PrivateRoute roles={['Admin']}><AdminDashboard /></PrivateRoute>} />
      <Route path="/admin/donations" element={<PrivateRoute roles={['Admin']}><AdminDonations /></PrivateRoute>} />
      <Route path="/admin/inventory" element={<PrivateRoute roles={['Admin']}><AdminInventory /></PrivateRoute>} />
      <Route path="/admin/beneficiaries" element={<PrivateRoute roles={['Admin']}><AdminBeneficiaries /></PrivateRoute>} />
      <Route path="/admin/volunteers" element={<PrivateRoute roles={['Admin']}><AdminVolunteers /></PrivateRoute>} />

      <Route path="/donor/dashboard" element={<PrivateRoute roles={['Donor']}><DonorDashboard /></PrivateRoute>} />
      <Route path="/donor/donate" element={<PrivateRoute roles={['Donor']}><DonorDonate /></PrivateRoute>} />
      <Route path="/donor/donations" element={<PrivateRoute roles={['Donor']}><DonorDonations /></PrivateRoute>} />
      <Route path="/donor/inventory" element={<PrivateRoute roles={['Donor']}><DonorInventory /></PrivateRoute>} />
      <Route path="/donor/track" element={<PrivateRoute roles={['Donor']}><DonorTrack /></PrivateRoute>} />

      <Route path="/ngo/dashboard" element={<PrivateRoute roles={['FoodBank']}><NgoDashboard /></PrivateRoute>} />
      <Route path="/ngo/donations" element={<PrivateRoute roles={['FoodBank']}><NgoDonations /></PrivateRoute>} />
      <Route path="/ngo/inventory" element={<PrivateRoute roles={['FoodBank']}><NgoInventory /></PrivateRoute>} />
      <Route path="/ngo/requests" element={<PrivateRoute roles={['FoodBank']}><NgoRequests /></PrivateRoute>} />

      <Route path="/beneficiary/request" element={<PrivateRoute roles={['Beneficiary']}><BeneficiaryRequest /></PrivateRoute>} />
      <Route path="/beneficiary/status" element={<PrivateRoute roles={['Beneficiary']}><BeneficiaryStatus /></PrivateRoute>} />
      <Route path="/beneficiary/food" element={<PrivateRoute roles={['Beneficiary']}><BeneficiaryFood /></PrivateRoute>} />
      <Route path="/beneficiary/foodbank" element={<PrivateRoute roles={['Beneficiary']}><BeneficiaryFoodBank /></PrivateRoute>} />

      <Route path="*" element={<Navigate to="/" replace />} />
    </Routes>
  );
}

export default function App() {
  return (
    <BrowserRouter>
      <AuthProvider>
        <AppRoutes />
        <FloatingChatbot />
      </AuthProvider>
    </BrowserRouter>
  );
}
