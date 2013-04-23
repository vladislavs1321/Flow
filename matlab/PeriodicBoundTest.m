function [X] = PeriodicBoundTest(X,L)
% L - is a positive size of a reservoir (along OX axis for examples)

% iCount = length(X);
%
%     if abs(X(i)) > VolSize
%       %  X(i) = mod(X(i) + VolSize,2*VolSize) - VolSize;
%          X(i) = mod(X(i) + VolSize,2*VolSize);
%     end   
% end

for k=1:length(X)
    if  abs(X(k)) > L  
      X(k)=X(k) - 2*L*floor( (X(k) + L)/(2*L) );
    end;
end;
 
 