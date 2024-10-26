import { Component, OnInit } from '@angular/core';
import { Router, RouterLink, RouterModule, RouterOutlet } from '@angular/router';
import { MatSlideToggleModule } from '@angular/material/slide-toggle';
import { CommonModule } from '@angular/common';
import { HeaderComponent } from '../header/header.component';
import { MatSnackBar, MatSnackBarModule } from '@angular/material/snack-bar';
import { HttpService } from '../../service/httpservice.service';
import { HttpClientModule } from '@angular/common/http';
import { Const } from '../../general/const';

@Component({
  selector: 'app-main-content',
  standalone: true,
  imports: [
    RouterOutlet,
    MatSlideToggleModule,
    CommonModule,
    RouterLink,
    RouterModule,
    HttpClientModule, // Ensure this is imported
    MatSnackBarModule
  ],
  templateUrl: './main-content.component.html',
  styleUrls: ['./main-content.component.scss'] // Corrected from 'styleUrl' to 'styleUrls'
})
export class MainContentComponent implements OnInit {
  dashboarddata: any;
  AppConst = new Const
  constructor(
    private snackBar: MatSnackBar,
    private httpService: HttpService, // Corrected capitalization
    public router: Router
  ) {}

  showSuccess(message: string): void {
    this.snackBar.open(message, 'Close', {
      duration: 3000,
      panelClass: ['toast-success']
    });
  }

  showError(message: string): void {
    this.snackBar.open(message, 'Close', {
      duration: 3000,
      panelClass: ['toast-error']
    });
  }

  ngOnInit(): void {
    setTimeout(() => {
      this.getCommentsForPost(1); // Assuming postId is 1
    }, 3000);
  }

  getCommentsForPost(postId: number): void {
    this.httpService.getComments(postId).subscribe(
      (response) => {
        console.log('Comments:', response);
        this.dashboarddata = response;
        this.showSuccess('API Called');
      },
      (error) => {
        console.error('Error fetching comments:', error);
        this.showError('An error occurred while fetching comments.');
      }
    );
  }

  route(): void {
    this.router.navigate(["A"]);
  }
}
