import { Component } from '@angular/core';
import { EditorModule } from 'primeng/editor';
@Component({
  selector: 'app-add-company',
  standalone: true,
  imports: [EditorModule],
  templateUrl: './add-company.component.html',
  styleUrl: './add-company.component.scss'
})
export class AddCompanyComponent {

}
