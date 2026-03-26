# Extension Director M&E Dashboard - Implementation Guide

## Overview

The Extension Director M&E (Monitoring & Evaluation) Dashboard is a professional, real-time monitoring system built with Laravel Livewire. It provides comprehensive analytics, charts, and performance metrics for extension programs.

## Features

### 1. **Key Metrics Dashboard**
- Total Programs count
- Active Programs count
- Planning Programs count
- Completed Programs count
- Active Communities count
- Monthly growth percentage
- Programs created this month

### 2. **Interactive Charts**

#### Programs by Status (Doughnut Chart)
- Visual breakdown of programs by status
- Color-coded segments:
  - 🟢 Active (Green)
  - 🔵 Planning (Blue)
  - 🟣 Completed (Purple)
  - ⚪ Inactive (Gray)

#### 6-Month Trend Chart (Line Chart)
- Historical program creation data
- Shows trends over the past 6 months
- Interactive data points

### 3. **Community Participation**
- Top 5 most active communities
- Program count per community
- Sorted by participation
- Real-time updates

### 4. **Recent Programs Table**
- Last 5 created programs
- Program title, status, communities involved
- Creation date
- Sortable columns

### 5. **Statistics Panel**
- Status breakdown with visual indicators
- Quick statistics:
  - Average programs per month
  - Completion rate percentage

### 6. **Professional Design Features**

#### Animations
- **Fade-in** on load (header)
- **Slide-up** with staggered delays (cards and sections)
- **Hover effects** with scale and lift transformations
- **Smooth transitions** on interactive elements
- **Slide-in-right** for list items

#### Color Scheme
- Professional gradient backgrounds
- Consistent color coding for statuses
- High contrast for readability
- Accessibility-first design

#### Typography & Layout
- Clear visual hierarchy
- Responsive grid system
- Clean spacing and padding
- Mobile-optimized layout

## Routes

### Admin Access
```
GET /admin/m-e-dashboard
```

### Secretary Access
```
GET /secretary/m-e-dashboard
```

## Navigation

The M&E Dashboard is accessible from:
- Admin Navigation: Dashboard → M&E Dashboard
- Secretary Navigation: Dashboard → M&E Dashboard
- Both desktop and mobile responsive menus

## Technical Stack

### Backend
- **Framework**: Laravel 11
- **Component**: Livewire 3
- **Language**: PHP 8.2+

### Frontend
- **CSS**: Tailwind CSS
- **Charts**: Chart.js 3.9.1
- **Animations**: CSS Keyframes + Tailwind transitions

### Database
- Queries extension_programs table
- Aggregates data in real-time
- Supports soft deletes

## Component Structure

### Livewire Component
**File**: `app/Livewire/ExtensionDirectorDashboard.php`

**Key Methods**:
- `mount()` - Initialization and authorization
- `loadDashboardData()` - Fetch all dashboard data
- `getCommunityParticipation()` - Calculate top communities
- `getMonthlyTrendData()` - Generate 6-month trend
- `refresh()` - Manual refresh action

**Properties**:
```php
public $totalPrograms = 0;
public $activePrograms = 0;
public $completedPrograms = 0;
public $planningPrograms = 0;
public $totalCommunities = 0;
public $programsThisMonth = 0;
public $programsGrowthPercent = 0;
public $recentPrograms = [];
public $communityParticipation = [];
public $programStatusData = []; // For Chart.js
public $monthlyTrendData = []; // For Chart.js
```

### Views
**Admin View**: `resources/views/admin/m-e-dashboard.blade.php`
**Secretary View**: `resources/views/secretary/m-e-dashboard.blade.php`
**Component View**: `resources/views/livewire/extension-director-dashboard.blade.php`

## Data Calculations

### Monthly Growth Percentage
```
Growth % = ((Programs This Month - Programs Last Month) / Programs Last Month) * 100
```

### Completion Rate
```
Completion Rate % = (Completed Programs / Total Programs) * 100
```

### Average per Month
```
Average = Total Programs / 12
```

## Charts Configuration

### Status Chart (Doughnut)
```javascript
{
  type: 'doughnut',
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom',
      labels: { padding: 20 }
    }
  }
}
```

### Trend Chart (Line)
```javascript
{
  type: 'line',
  responsive: true,
  tension: 0.4,
  fill: true,
  scales: {
    y: { beginAtZero: true }
  }
}
```

## Animation Details

### CSS Keyframes

**Fade In**
```css
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
Duration: 0.6s ease-out
```

**Slide Up**
```css
@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
Duration: 0.6s ease-out
Staggered delays for cascade effect
```

**Slide In Right**
```css
@keyframes slideInRight {
  from { opacity: 0; transform: translateX(-20px); }
  to { opacity: 1; transform: translateX(0); }
}
Applied to community list items
```

### Hover Effects

**Metric Cards**
- Scale: 105%
- Lift: -4px (translateY)
- Shadow enhancement
- Transition: 300ms

**Community List Items**
- Background color shift (to blue-50)
- Horizontal shift: +4px (translateX)
- Transition: 200ms

**Table Rows**
- Background color shift (to blue-50)
- Shadow enhancement
- Transition: 150ms

**Buttons**
- Color change
- Shadow enhancement
- Transition: 200ms

## Responsive Design

### Breakpoints
- **Mobile**: < 768px
- **Tablet**: 768px - 1024px
- **Desktop**: > 1024px

### Responsive Elements
- Grid columns adjust (1 → 2 → 5 columns)
- Table becomes scrollable on mobile
- Navigation collapses on mobile
- Charts maintain aspect ratio

## Authorization

Both components enforce role-based access:
```php
Allowed: 'admin', 'secretary'
Denied: 'viewer' or unauthenticated
Throws: AuthorizationException
```

## Refresh Functionality

**Manual Refresh Button**
- Reloads all dashboard data
- Re-runs all queries
- Maintains animations
- Uses Livewire wire:click action

## Performance Considerations

### Data Caching (Recommended)
```php
// In component mount
$this->dashboardData = Cache::remember(
    'dashboard_data_' . auth()->id(),
    300, // 5 minutes
    fn() => $this->loadDashboardData()
);
```

### Query Optimization
- Uses efficient aggregation functions
- Minimizes database hits
- Groups by status in one query
- Uses collection methods for client-side filtering

## Customization

### Add New Metrics
1. Add property to Livewire component
2. Set value in `loadDashboardData()`
3. Create new metric card in view
4. Adjust grid columns if needed

### Modify Colors
Update in chart configuration:
```javascript
backgroundColor: [
  'rgba(34, 197, 94, 0.8)',  // green
  'rgba(59, 130, 246, 0.8)',  // blue
  // Add more as needed
]
```

### Change Animation Timing
Modify in `extension-director-dashboard.blade.php`:
```css
.animate-slide-up {
  animation: slideUp 0.6s ease-out both; /* Change duration/easing */
}
```

## Troubleshooting

### Charts Not Rendering
- Ensure Chart.js script is loaded
- Check console for JavaScript errors
- Verify data is passed to view
- Check browser compatibility

### Animations Not Working
- Clear browser cache
- Check CSS @keyframes syntax
- Verify Tailwind CSS is compiled
- Use browser dev tools to inspect

### Data Not Updating
- Click refresh button
- Check database for data
- Verify Laravel cache is cleared
- Check for SQL errors in logs

### Authorization Issues
- Ensure user role is 'admin' or 'secretary'
- Check middleware in web.php
- Verify AuthorizationException is not thrown

## Future Enhancements

1. **Export Functionality**
   - Export charts as images
   - Export data as CSV/Excel
   - Generate PDF reports

2. **Advanced Filtering**
   - Filter by date range
   - Filter by specific communities
   - Filter by program type

3. **Real-time Updates**
   - WebSocket integration
   - Auto-refresh every 5 minutes
   - Push notifications

4. **Additional Metrics**
   - Budget tracking
   - Beneficiary count
   - Activity completion rates
   - Impact metrics

5. **Custom Reports**
   - Report builder
   - Scheduled reports
   - Email delivery

## File Locations

```
SmartCEMES_FINAL/
├── app/Livewire/
│   └── ExtensionDirectorDashboard.php
├── resources/views/
│   ├── admin/
│   │   └── m-e-dashboard.blade.php
│   ├── secretary/
│   │   └── m-e-dashboard.blade.php
│   └── livewire/
│       └── extension-director-dashboard.blade.php
└── routes/web.php (updated)
```

## Dependencies

- Laravel 11
- Livewire 3
- Tailwind CSS
- Chart.js 3.9.1
- PHP 8.2+

## Browser Support

- Chrome (Latest)
- Firefox (Latest)
- Safari (Latest)
- Edge (Latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

---

**Created**: March 2026
**Version**: 1.0.0
**Status**: Production Ready
**Last Updated**: March 18, 2026
