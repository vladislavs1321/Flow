%% Initialization section 
 clear all;
 close all;
%loadlibrary('BEMatlabLib.dll', 'MatlabLibBE.h');

% File with photon arrival times

ResultsFile='Flow_test.txt';                    % The file with the output flow
DescriptionFile=['Description_',ResultsFile]; % The file with description of the parameters
format_string='%s\n'; 
format_float='%21.15e\n';

fid_1=fopen(ResultsFile,'wt');
fid_2=fopen(DescriptionFile,'wt');


fprintf(fid_1,format_string,'ID:FLOWDATABE');
fprintf(fid_1,format_string,'V :T1');



% Brightness function parameters
w0 = 0.3e-6;    % in meters
z0 = 0.9e-6;    % in meters
F = 0.4;          % Correction factor 

 % The modelling time
StartTime=0;
EndTime=60;

% Modelling area
R.Xb=10*w0;
R.Yb=10*w0;
R.Zb=10*z0;

% Volumes
R.V =8*R.Xb * R.Yb * R.Zb;

% Standard volume of FCS
Veff=(1+F)*(1+F)*(pi^1.5)*w0*w0*z0;

% Molecules

Molecules.Diffusion = 2.8e-10;
Molecules.Brightness = 100000; % in Hz
Molecules.Neff = 0.01;

Molecules.SimulMeanCount = R.V * Molecules.Neff/Veff;
Molecules.DiffusionTime=w0*w0/(4*Molecules.Diffusion);
Molecules.Count = round(Molecules.SimulMeanCount);

NumberOfEvents=0;
FNumberOfEvents=0;

Intensity=(1+F)*Molecules.Brightness;

InvI=1/Intensity;
PreviousEvent=0;  % For Brownian motion 
CurrentEvent=0;   % For Brownian motion


fprintf(fid_2,format_string,['Brightness profile parameters, m: w0=',num2str(w0),' z0=',num2str(z0)]);
fprintf(fid_2,format_string,['Correction factor, F: ',num2str(F)]);
fprintf(fid_2,format_string,['The total number of molecules: ',num2str(Molecules.Count)]);
fprintf(fid_2,format_string,['Molecule brightness, Hz: ',num2str(Molecules.Brightness)]);
fprintf(fid_2,format_string,['Molecules diffusion time, s: ',num2str(Molecules.DiffusionTime)]);
fprintf(fid_2,format_string,['Neff: ',num2str(Molecules.Neff)]);



%% Simulation

% Generate the flow for every molecule
 for k=1:Molecules.Count
          
    % Uniformly distribute a molecule in the reservoir    
      Molecules.X = (2*rand() - 1)*R.Xb; 
      Molecules.Y = (2*rand() - 1)*R.Yb;
      Molecules.Z = (2*rand() - 1)*R.Zb; 
    
      PreviousEvent=StartTime;    % Start generation from this moment of time
      CurrentEvent=PreviousEvent-InvI*log(rand());  % The first event of the flow

         if CurrentEvent < EndTime
               while 1>0

                  PreviousEvent=CurrentEvent;
                  CurrentEvent=PreviousEvent-InvI*log(rand());

                      if (CurrentEvent > EndTime) 
                          break; 
                      end;

                  % Decimation of the flow
                  if rand()*Intensity < (1+F)*Molecules.Brightness*B_function(Molecules.X,Molecules.Y,Molecules.Z,w0,z0)
                     % // The true condition is "<="
                    % fprintf(fid_1,format_float,PreviousEvent);
                     NumberOfEvents=NumberOfEvents+1;                     
                     Events( NumberOfEvents)=PreviousEvent;
                                          
                  end; 
                  % Brownian Movement of a molecule
                  
                    Sigma = sqrt(2*Molecules.Diffusion*(CurrentEvent-PreviousEvent));
              
                    Molecules.X=normrnd(Molecules.X,Sigma);
                    Molecules.Y=normrnd(Molecules.Y,Sigma);
                    Molecules.Z=normrnd(Molecules.Z,Sigma);
                  
                  %  Periodic boundary conditions    
                    Molecules.X = PeriodicBoundTest(Molecules.X,R.Xb);
                    Molecules.Y = PeriodicBoundTest(Molecules.Y,R.Yb);
                    Molecules.Z = PeriodicBoundTest(Molecules.Z,R.Zb);
                  
                end;
         end;

    disp(['Current molecule ',num2str(k),' from ',num2str(Molecules.Count)]);
    
end;
      
    % Out of focus correction contribution
         if F>0
           Intensity=Molecules.Neff*Molecules.Brightness*F/((1+F)*sqrt(8));
           InvI=1/Intensity;
 
           PreviousEvent=StartTime;    % Start generation from this moment of time
           CurrentEvent=PreviousEvent - InvI*log(rand());  % The first event of the flow
 
              if CurrentEvent < EndTime

                 while 1>0

                   PreviousEvent=CurrentEvent;
                   CurrentEvent=PreviousEvent - InvI*log(rand());

                   if (CurrentEvent > EndTime) 
                       break;
                   end;

                     NumberOfEvents=NumberOfEvents+1;                     
                     Events( NumberOfEvents)=PreviousEvent;
                     FNumberOfEvents=FNumberOfEvents+1;
                   end;
              end;

         end;

       Events=sort(Events);
       fprintf(fid_1,format_float,Events);

 fclose(fid_1);
 fclose(fid_2);
 
