#include <stdafx.h>
#include <iostream>
#include <stdlib.h>  
#include <conio.h>
#include <math.h>
#include <cmath>
#include <fstream>
#include <time.h>

#include <set>


#include "RandomGenOptions.h"

using namespace std;

#define UNIFORM_RV dsfmt_genrand_close_open(&dsfmt)

dsfmt_t dsfmt;      // Structure for generator's functioning



double B_function( double x, double y, double z, double w0_0, double z0_0)
	{
		
		 static double a=-2.0/(w0_0*w0_0);
		 static double b=-2.0/(z0_0*z0_0);
		 return exp( a*(x*x + y*y) + b*z*z );
	}

double PeriodicBoundTest(double X, double L)
{
	if   ( abs(X) > L )	{
		X=X - 2*L*floor( (X + L)/(2*L) );
	}
	return X;
}

double normrnd( double dM, double dD){

// In the result of work of this generator one recives
// two gauss random value. One of them is used as a result (dNVal2)
// and one stays for the next call of function due too C++
// mechanism of static variables

 static bool bFlag_Val2 = false;
 static double dNVal2;
 static double dFac,dR,dUVal1,dUVal2;

 if (bFlag_Val2)
  { // We have an extra deviate handy, so
   bFlag_Val2 = false; // so unset the flag,
   return dNVal2*dD + dM; // and return it.
  }
 do
  {
   dUVal1 = 2.0*UNIFORM_RV -1.0; // pick two uniform numbers in the square extending from -1 to +1 in each direction
   dUVal2 = 2.0*UNIFORM_RV -1.0;

   dR = dUVal1*dUVal1 + dUVal2*dUVal2;	// see if they are in the unit circle,
  }
 while(dR >= 1.0 || dR == 0.0); // and if they are not, try again.

 dFac = sqrt(-2.0*log(dR)/dR);
 // Now make the Box-Muller transformation to get two normal deviates.
 // Return one and save the other for next time.
 dNVal2 = dUVal1*dFac;
 bFlag_Val2 = true; // Set flag.
 return dUVal2*dFac*dD + dM;
}

int main()
{
	srand( (unsigned)time( NULL ) );
	char FileName[]="Flow_test.txt";

    int seed = 12345;   // Initial value of generator (set any int value)
    dsfmt_t dsfmt;      // Structure for generator's functioning

    dsfmt_init_gen_rand(&dsfmt, seed); // Initialize generator



	ofstream ofs_Flow(FileName);//создаются выходной поток и текстовый файл  The file with the output flow
	int OutputPrecision=10;


   ofs_Flow<<"ID:"<<"FLOWDATABE"<<'\n'<<"V :"<<"T1"<<'\n';
   ofs_Flow.precision(OutputPrecision);

   
	/*
    if ( !ofs_Flow.bad() ) //проверка успешности открытия потока
    {
        ofs_Flow << "Writing to a basic_ofstream object..." << endl;
        ofs_Flow.close();
	}
	*/

	//ofs_Flow << "Writing to a basic_ofstream object..." << endl;
	/*double d=0.0;

	 for ( int k=0; k < 100; k++ )
     {
		 d= dsfmt_genrand_close_open(&dsfmt); // And ... this is a random value!
	 }
	*/

	

 // Brightness function parameters
 double w0 = 3e-7;    // in meters
 double z0 = 9e-7;    // in meters
 double F = 0.0;        // Correction factor 

 double BB=B_function(0.0, 0.0,0.0, w0, z0);

 // The modelling time
double StartTime=0.0;
double EndTime=20 ;

//% Modelling area
double R_Xb=10*w0;
double R_Yb=10*w0;
double R_Zb=10*z0;

// Volumes
double R_V =8*R_Xb * R_Yb * R_Zb;

//% Standard volume of FCS
const double PI = 3.1415926535897932384626433832795;
double Veff=(1+F)*(1+F)*( pow(PI,1.5) )*w0*w0*z0;

//% Molecules

double Molecules_Diffusion = 2.8e-10;
double Molecules_Brightness = 100000; // in Hz
double Molecules_Neff = 0.01;

double Molecules_SimulMeanCount = R_V * Molecules_Neff/Veff;
double Molecules_DiffusionTime=w0*w0/(4*Molecules_Diffusion);
///????
double Molecules_Count = floor(Molecules_SimulMeanCount+0.5); 
//????

int NumberOfEvents=0;
int FNumberOfEvents=0;

double Intensity=(1+F)*Molecules_Brightness;

double InvI=1.0/Intensity;
double PreviousEvent=0;  //% For Brownian motion 
double CurrentEvent=0;   //% For Brownian motion

double CurrIntensity=0.0;


// Triplet
double Kab=1.1e6; //in Hz
double Kba=0.49e6;

double Ta=1/Kab;
double Tb=1/Kba;

double Pa=Kba/(Kab+Kba);
double Pb=Kab/(Kab+Kba);

char State;
double CurTau=0.0;
bool flag=false;

// Simulation

// Generate the flow for every molecule
 for ( int k=0; k < Molecules_Count; k++ )
 {          

	 cout<<"Current molecule "<<k<<'\n';
    // Uniformly distribute a molecule in the reservoir    
    double Molecules_X = (2.0*UNIFORM_RV - 1.0)*R_Xb; 
    double Molecules_Y = (2.0*UNIFORM_RV - 1.0)*R_Yb;
	double Molecules_Z = (2.0*UNIFORM_RV - 1.0)*R_Zb; 
    
    PreviousEvent=StartTime;  //  % Start generation from this moment of time
    CurrentEvent=PreviousEvent - InvI*log(UNIFORM_RV) ;  //% The first event of the flow

		 // State
      if ( Pa > UNIFORM_RV )
	     State = 'A';
      else State = 'B';

	  CurTau=0.0;

         if ( CurrentEvent < EndTime )
		 {

               while ( true )
			   {
                  PreviousEvent=CurrentEvent;
                  CurrentEvent=PreviousEvent - InvI*log(UNIFORM_RV);

                  if (CurrentEvent > EndTime) 
				  {			   
                      break;                      
				  }			   
				  BB=B_function(Molecules_X,Molecules_Y,Molecules_Z,w0,z0);
				  CurrIntensity=Molecules_Brightness*BB;
                  //  % Decimation of the flow

                  if ( UNIFORM_RV*Intensity < CurrIntensity )
				  {
                  /*
                     NumberOfEvents=NumberOfEvents+1;                     
//                     Events( NumberOfEvents)=PreviousEvent;                                          
                     ofs_Flow<<PreviousEvent<<endl;

						   //ofs_Flow << "Writing to a basic_ofstream object..." << endl;

                 */

					  //-------------------------------------------------------
					  flag = true;

					  while (flag){
						 
					  if (State == 'A') {
							  if (PreviousEvent < CurTau) {
							     NumberOfEvents=NumberOfEvents+1;                     
//						         Events( NumberOfEvents)=PreviousEvent;                                          
						         ofs_Flow<<PreviousEvent<<endl;
								 flag=false;
							  }
							  else {
								 CurTau += -Tb*log(UNIFORM_RV);
							     State='B';
							  }
									  
						  }

					  else {
							 if (PreviousEvent > CurTau) {
							  CurTau += -Ta*log(UNIFORM_RV);
							  State='A';
							 }
							 else{
								 flag=false;
							  }
						  }
					  }
					  //-------------------------------------------------------
					
	

				  }
                  // % Brownian Movement of a molecule
                  
                  double Sigma = sqrt(2*Molecules_Diffusion*(CurrentEvent-PreviousEvent));
              
				  Molecules_X=dSFMT_NormRandGenerator(&dsfmt, Molecules_X, Sigma);
				  Molecules_Y=dSFMT_NormRandGenerator(&dsfmt, Molecules_Y, Sigma);
				  Molecules_Z=dSFMT_NormRandGenerator(&dsfmt, Molecules_Z, Sigma);

				  /*
                  Molecules_X=normrnd(Molecules_X,Sigma);
                  Molecules_Y=normrnd(Molecules_Y,Sigma);
                  Molecules_Z=normrnd(Molecules_Z,Sigma);
                  */
                  // %  Periodic boundary conditions    
                  Molecules_X = PeriodicBoundTest(Molecules_X,R_Xb);
                  Molecules_Y = PeriodicBoundTest(Molecules_Y,R_Yb);
                  Molecules_Z = PeriodicBoundTest(Molecules_Z,R_Zb);                  
			   }
		}
}      

 /*
   // % Out of focus correction contribution
         if ( F>0 )
		 {
           Intensity=Molecules_Neff*Molecules_Brightness*F/((1+F)*double( sqrt(double (8) ) ));
           InvI=1/Intensity;
 
           PreviousEvent=StartTime;							 // % Start generation from this moment of time
           CurrentEvent=PreviousEvent - InvI*log( UNIFORM_RV);  //% The first event of the flow
 
           if ( CurrentEvent < EndTime )
		   {
                 while( 1>0 )
				 {
                     PreviousEvent=CurrentEvent;
                     CurrentEvent=PreviousEvent - InvI*log(UNIFORM_RV);

                     if ( CurrentEvent > EndTime ) 
				     {
                       break;             
				     }
                     NumberOfEvents=NumberOfEvents+1;                     
   //                  Events( NumberOfEvents)=PreviousEvent;
					
					 ofs_Flow<<PreviousEvent<<endl;
						   //ofs_Flow << "Writing to a basic_ofstream object..." << endl;
					 
					 

                     FNumberOfEvents=FNumberOfEvents+1;
              	 }		        
		   }
   		 }	
		 */
    //   Events=sort(Events);

       ofs_Flow.close();

	   multiset<double> sorted_data; //Объявляем шаблонный класс упорядоченного множетсва.
	ifstream in(FileName);
	char str[30];

	if(!in)
	{
		cout << "Cannot open file." << endl;
	}

	else
	{
		in.getline(str,25);
		in.getline(str,25);

		while (in) //Читаем файл
		{
			double tmp;
			in >> tmp;
			sorted_data.insert(tmp);
		}
	}

		in.close();
		cout << "Lines count: " << sorted_data.size() << endl; //Количество прочитанных строк.



		ofstream out("output.txt");
		out<<"ID:"<<"FLOWDATABE"<<'\n'<<"V :"<<"T1"<<'\n';
        out.precision(OutputPrecision);

		if(!out) 
		{
			cout << "Can't open output file";
			
		}
		//Записываем в выходной файл числа в отсотртированном виде.
		multiset<double>::const_iterator i = sorted_data.begin();
		multiset<double>::const_iterator ie = sorted_data.end();
		for (; i != ie; ++i)
		{
			out << *i << endl;
		}
		out.close();
	





	}



//
//
//// Cartesian coordinates with the origin in (0,0,0)
//// All distances are measured out in the positive side (Example: -OX <= x <= OX )
//
//  OXX=2.0*OX;
//  OYY=2.0*OY;
//  OZZ=2.0*OZ;
//
//  invOXX=1.0/OXX;
//  invOYY=1.0/OYY;
//  invOZZ=1.0/OZZ;
//
//void TBox::BorderConditions(double *X, double *Y, double *Z)
//  // X[0] - the current position of a molecule
//{
//  if ( fabs(X[0]) > OX ) X[0]=X[0] - OXX*floor((X[0] + OX)*invOXX);
//  if ( fabs(Y[0]) > OY ) Y[0]=Y[0] - OYY*floor((Y[0] + OY)*invOYY);
//  if ( fabs(Z[0]) > OZ ) Z[0]=Z[0] - OZZ*floor((Z[0] + OZ)*invOZZ);
//};