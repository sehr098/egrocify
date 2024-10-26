import { Component } from '@angular/core';
import {MatTableDataSource, MatTableModule} from '@angular/material/table';
import {MatIconModule} from '@angular/material/icon';
import { ColumnDefinition, SharedTableComponent, TableAction, TableData } from '../../../shared/shared-table/shared-table.component';

@Component({
  selector: 'app-user-list',
  standalone: true,
  imports: [MatTableModule,MatIconModule,SharedTableComponent],
  templateUrl: './user-list.component.html',
  styleUrl: './user-list.component.scss'
})
export class UserListComponent {
  companyData: TableData[] = [
    { id: 1, ReferalCode: 'Test Company', Name: 'test-company', Email: 1, Package: 1, Phone: 1, CurrentBalance: 1, TotalUsers : 1},
    { id: 2, ReferalCode: 'Another Company', Name: 'another-company', Email: 2, Package: 5, Phone: 1, CurrentBalance: 1, TotalUsers : 1},
    { id: 3, ReferalCode: 'Test Company', Name: 'test-company', Email: 1, Package: 1, Phone: 1, CurrentBalance: 1, TotalUsers : 1},
    { id: 4, ReferalCode: 'Another Company', Name: 'another-company', Email: 2, Package: 5, Phone: 1, CurrentBalance: 1, TotalUsers : 1},
    { id: 5, ReferalCode: 'Test Company', Name: 'test-company', Email: 1, Package: 1, Phone: 1, CurrentBalance: 1, TotalUsers : 1},
    { id: 6, ReferalCode: 'Another Company', Name: 'another-company', Email: 2, Package: 5, Phone: 1, CurrentBalance: 1, TotalUsers : 1},
    { id: 7, ReferalCode: 'Test Company', Name: 'test-company', Email: 1, Package: 1, Phone: 1, CurrentBalance: 1, TotalUsers : 1},
    { id: 8, ReferalCode: 'Another Company', Name: 'another-company', Email: 2, Package: 5, Phone: 1, CurrentBalance: 1, TotalUsers : 1},
    { id: 9, ReferalCode: 'Test Company', Name: 'test-company', Email: 1, Package: 1, Phone: 1, CurrentBalance: 1, TotalUsers : 1},
    { id: 10, ReferalCode: 'Another Company', Name: 'another-company', Email: 2, Package: 5, Phone: 1, CurrentBalance: 1, TotalUsers : 1},
    { id: 11, ReferalCode: 'Another Company', Name: 'another-company', Email: 2, Package: 5, Phone: 1, CurrentBalance: 1, TotalUsers : 1},
    // Add more data as needed
  ];

  columns: ColumnDefinition[] = [
    { key: 'ReferalCode', header: 'Referal Code', backgroundColor: 'orange', textColor: 'black', customdiv: true },
    { key: 'Name', header: 'Name' },
    { key: 'Email', header: 'Email', backgroundColor: 'purple', textColor: 'black', customdiv: true },
    { key: 'Package', header: 'Package', backgroundColor: 'lightblue', textColor: 'black', customdiv: true },
    { key: 'Phone', header: 'Phone', backgroundColor: 'lightblue', textColor: 'black', customdiv: true },
    { key: 'CurrentBalance', header: 'Current Balance' , backgroundColor: 'brown', textColor: 'black', customdiv: false },
    { key: 'TotalUsers', header: 'Total Users' , backgroundColor: 'peach', textColor: 'black', customdiv: false },
  ];

  actions: TableAction[] = [
    { label: 'Action 1', action: this.action1 },
    { label: 'Action 2', action: this.action2 },
    { label: 'Action 3', action: this.action3 },
    { label: 'Action 4', action: this.action4 },
  ];

  action1(element: TableData) {
    console.log('Action 1 clicked', element);
  }

  action2(element: TableData) {
    console.log('Action 2 clicked', element);
  }

  action3(element: TableData) {
    console.log('Action 3 clicked', element);
  }

  action4(element: TableData) {
    console.log('Action 4 clicked', element);
  }
}
